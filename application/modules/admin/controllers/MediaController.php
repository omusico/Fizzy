<?php

/**
 * Description of Media
 *
 * @author mattijs
 */
class Admin_MediaController extends Fizzy_SecuredController
{
    /**
     * @todo implement overwrite checkbox
     */
    public function indexAction()
    {
        $uploadFolder = Fizzy::getInstance()->getPath('uploads');

        $form = $this->_getForm($uploadFolder);

        if($this->_request->isPost()) {
            if($form->isValid($_POST) && $form->file->receive()) {
                $this->addSuccessMessage('File was successfully uploaded.');
            }
            else {
                foreach($form->getErrorMessages() as $message) {
                    $this->addErrorMessage($message);
                }
            }
            $this->_redirect('/fizzy/media');
        }

        # Parse all files in the upload directory
        $files = array();
        foreach(new DirectoryIterator($uploadFolder) as $file) {
            if($file->isFile()) {
                $fileInfo = array(
                    'type' => substr(strrchr($file->getBaseName(), '.'), 1),
                    'basename' => $file->getBaseName(),
                    'path' => $file->getPath(),
                    'size' => $file->getSize(),
                );
                $files[] = (object) $fileInfo;
            }
        }

        # Render the view
        $this->view->uploadFolder = $uploadFolder;
        $this->view->files = $files;
        $this->view->form = $form;


        $this->renderScript('media.phtml');
    }

    public function deleteAction()
    {
        $name = $this->_getParam('name');

        if(null !== $name)
        {
            $name = basename(urldecode($name));
            $uploadFolder = Fizzy::getInstance()->getPath('uploads');
            $file = $uploadFolder . DIRECTORY_SEPARATOR . $name;
            if(is_file($file))
            {
                unlink($file);
                $this->addSuccessMessage('File was successfully deleted.');
            }
        }

        $this->_redirect($this->view->baseUrl('/fizzy/media'));
    }

    /**
     * Returns the form for file uploads
     * @param string $uploadFolder
     * @return Zend_Form
     */
    protected function _getForm($uploadFolder)
    {
        $formConfig = array (
            'action' => $this->view->baseUrl('/fizzy/media'),
            'enctype' => 'multipart/form-data',
            'elements' => array(
                'file' => array (
                    'type' => 'file',
                    'options' => array (
                        'label' => 'File',
                        'description' => 'Choose a file to upload. The maximun file size is ' . str_replace(array('K', 'M', 'G'), array(' KB', ' MB', ' GB'), ini_get('upload_max_filesize')) . '.',
                        'required' => true,
                        'destination' => $uploadFolder,
                    )
                ),
                /*'overwrite' => array (
                    'type' => 'checkbox',
                    'options' => array (
                        'label' => 'Overwrite existing?',
                        'description' => 'If this is checked any existing file with the same name will be overridden.',
                        'required' => false,
                    )
                ),*/
                'submit' => array (
                    'type' => 'submit',
                    'options' => array (
                        'label' => 'Upload',
                        'ignore' => true,
                    )
                )
            ),
        );

        return new Zend_Form(new Zend_Config($formConfig));
    }
    
}