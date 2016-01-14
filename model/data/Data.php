<?php
namespace of;

class Data extends Node {

    private $in;

    public function __construct()
    {
        parent::__construct();
        $this->setTableName('data');
        $this->in = http_input();
    }


    public function runAjax() {
        switch( $doing = http_input('what') ) {
            case 'file-upload' :
                return data()->ajaxFileUpload();
            case 'file-delete' :
                return data()->ajaxFileDelete();
            case 'file-finish' :
                return data()->ajaxFileFinish();
            default :
                json_error(-40445, 'No what to do');
                return null;
        }
    }

    public function upload( $form_name ) {


        $this->deleteUnfishedUploads();

        if ( ! isset($_FILES[$form_name]) ) {
            sys()->log("No file selected on upload box.");
            return FALSE;
        }

        $_file = $_FILES[$form_name];
        if ( ! is_uploaded_file($_file['tmp_name'])) return isset($_file['error']) ? $_file['error'] : 4;

        $filename = $this->getNextFilename($_file['name']);

        $path = $this->path($filename);
        if ( ! move_uploaded_file($_file["tmp_name"], $path) ) {
            sys()->log("ERROR: move_uploaded_file($_file[tmp_name], $path)");
            return -4004901;
        }

        $data = $this->create();
        $data->set('name', $_file['name']);
        $data->set('mime', $_file['type']);
        $data->set('size', $_file['size']);
        $data->set('name_saved', $filename);
        $data->set('finish', 0);
        $data->set('code', $this->in['code']);
        $data->set('gid', $this->in['gid']);

        $data->save();

        return $data->get('id');
    }

    public function getErrorString($code) {
        switch ($code)
        {
            case UPLOAD_ERR_INI_SIZE: return 'upload_file_exceeds_limit';
            case UPLOAD_ERR_FORM_SIZE: return 'upload_file_exceeds_form_limit';
            case UPLOAD_ERR_PARTIAL: return 'upload_file_partial';
            case UPLOAD_ERR_NO_FILE: return 'upload_no_file_selected';
            case UPLOAD_ERR_NO_TMP_DIR: return 'upload_no_temp_directory';
            case UPLOAD_ERR_CANT_WRITE: return 'upload_unable_to_write_file';
            case UPLOAD_ERR_EXTENSION: return 'upload_stopped_by_extension';
            case -4004901 : return 'Uploaded file movement failed';
            default: return 'upload_no_file_selected';
        }
    }


    private function deleteUnfishedUploads()
    {
        $stamp = time() - 60 * 60 * 4; // 4 hours.
        $entities = $this->loadQuery("finish=0 AND created<$stamp");
        if ( $entities ) {
            foreach ( $entities as $data ) {
                $data->delete();
            }
        }
    }


    /**
     * @return int
     */
    public function delete() {
        //sys()->log("Data::delete() this:");
        //sys()->log($this);
        if ( $this->is() ) {
            sys()->log("Data::delete() : path: " . $this->get('path'));
            @unlink($this->get('path'));
            parent::delete();
            return 0;
        }
        else {
            sys()->log("Data::delete() : data object does not exists. File does not exists.");
            return -1;
        }
    }


    /**
     * Backward Overriding
     * @param $id
     * @param string $fields
     * @return Entity
     */
    public function load($id, $fields='*') {
        //sys()->log("Data::load($id)");
        $data = parent::load($id, $fields);
        if ( $data->is() ) {
            $data->set( 'url', url_overframe_data($this->get('name_saved')) );
            $data->set( 'path', $this->path( $this->get('name_saved') ) );
        }
        return $data;
    }

    private function path($filename) {
        return dir_overframe_data() . '/' . $filename;
    }


    /**
     *
     */
    public function install() {
        $this->init();
        $this->addColumn('gid', 'varchar', 64);
        $this->addColumn('code', 'varchar', 64);
        $this->addColumn('finish', 'char');
        $this->addColumn('name', 'varchar', 255);
        $this->addColumn('name_saved', 'varchar', 255);
        $this->addColumn('mime', 'varchar', 64);
        $this->addColumn('size', 'int');
        $this->addIndex('gid');
        $this->addIndex('code');
    }

    public function uninstall()
    {
        $this->uninit();
    }

    private function getNextFilename($filename) {
        $filename = $this->makeSafeFilename($filename);
        if ( ! is_file($this->path($filename)) ) return $filename;

        $pi = pathinfo($filename);
        for ( $i=1; $i<10000; $i++ ) {
            if ( ! empty( $pi['extension'] ) ) $new_name = $pi['filename'] . "-$i." . $pi['extension'];
            else {
                $new_name = $pi['filename']. "-$i";
            }
            if ( ! is_file($this->path($new_name)) ) return $new_name;
        }
        return unique_id();
    }

    private function makeSafeFilename($filename)
    {
        // Remove any trailing dots, as those aren't ever valid file names.
        $filename = rtrim($filename, '.');
        $filename = str_replace(' ', '-', $filename);
        $regex = array('#(\.){2,}#', '#[^A-Za-z0-9\.\_\- ]#', '#^\.#');
        $filename = trim(preg_replace($regex, '', $filename));
        return $filename;
    }

    /**
     *
     * @note 모든 Ajax 파일 업로드는 이 함수 하나만을 통해서 업로드해야 한다.
     * README 파일 참고
     */
    public function ajaxFileUpload()
    {
        $re = $this->upload('userfile');
        if ( $re < 0 ) json_error( $re, data()->getErrorString($re) );
        else json_success( data()->load($re)->getRecord() );
    }

    public function ajaxFileDelete()
    {
        sys()->log("ajaxFileDelete() : id=" . $this->in['id']);
        if ( $code = data($this->in['id'])->delete() ) json_error($code, "failed to delete file");
        else json_success(array('id'=>$this->in['id']));
    }

    public function ajaxFileFinish()
    {
        sys()->log("ajaxFileFinish() : id=" . $this->in['id']);
        if ( $code = data($this->in['id'])->finish() ) json_error($code, "failed to finish file");
        else json_success(array('id'=>$this->in['id']));
    }

    public function finish()
    {
        sys()->log("Data::finish() : id=" . $this->get('id'));
        if ( $this->is() ) {
            $this->put('finish', 1);
            return 0;
        }
        else return -1;
    }

}