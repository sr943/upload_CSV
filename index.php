        <?php

        //turn on debugging messages
        ini_set('display_errors', 'On');
        error_reporting(E_ALL);


        //instantiate the program object

        //Class to load classes it finds the file when the progrm starts to fail for calling a missing class
        class Manage {
            public static function autoload($class) {
                //you can put any file name or directory here
                include $class . '.php';
            }
        }

        spl_autoload_register(array('Manage', 'autoload'));

        //instantiate the program object
        $obj = new main();

        class main {


            public function __construct(){

         //set default page request when no parameters are in URL
                $pageRequest ='homepage';
                //check if there are parameters
                 if(isset($_REQUEST['page'])) {

                    //load the type of page the request wants into page request
                   
                    $pageRequest = $_REQUEST['page'];
                }
         //instantiate the class that is being requested
                 $page = new $pageRequest;

                 if($_SERVER['REQUEST_METHOD'] == 'GET') {
                    $page->get();
                } else {
                    $page->post();
                }



            }
        }


        abstract class page {
            protected $html;

            public function __construct()
            {
                $this->html .= '<html>';
                $this->html .= '<body>';
            }
            public function __destruct()
            {
                $this->html .= '</body></html>';
                print_r($this->html);
            }

            
        }



        class globals
        {
            
            public static function all()

            {
                // Create an upload form
                 $form = '<form action="index.php?page=choosefile" method="post" enctype="multipart/form-data">';
               
                $form .= '<input type="file" name="fileToUpload" id="fileToUpload">';
                $form .= '<br> <br>';
               
                $form .= '<input type="submit" value="Upload" name="submit">';
                $form .= '</form> ';
                return $form;

            }
        }




        class homepage extends page {

            public function get() {

                 $this->html .= '<h1>Upload Form</h1>';
                $this->html .= globals::all();
            }

        }



        class  setHeader
        {
            
            public static function set($target_file)

            {
                
                header('Location: index.php?page=display&filename=' .$target_file );
                
            }
        }



        class choosefile {



        public function post(){


            $target_dir = "file_uploads/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);


             
             $csv_mimetypes = array(
            'text/csv',
            'text/plain',
            'application/csv',
            'text/comma-separated-values',
            'application/excel',
            'application/vnd.ms-excel',
            'application/vnd.msexcel',
            'text/anytext',
            'application/octet-stream',
            'application/txt',
        );
             


            if(($_FILES["fileToUpload"]["size"] > 5000000) || (!in_array($_FILES['fileToUpload']['type'], $csv_mimetypes))){           
                echo "<b><h3>Sorry, your file is too large or not in CSV format.</h3></b>";
            
        }
            
        else if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)){        
                echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                setHeader::set($target_file);
                
            }    
                 
        }


        }

        class display extends page{

            public function get(){

         
         $file= fopen($_GET['filename'],"r");
         $this->html .= '<h1>Table</h1>';
        $this->html .= '<table border ="1">';
        
        while (!feof($file)) {
            $line=fgetcsv($file);

           
            $a=(array) $line;
           
            foreach ($a as $value ) {
               $this->html .= "<td> $value </td>";
            }
            $this->html .=  '</tr>';
        }
        $this->html .=  '</table>';

         
        fclose($file);

}}


        ?>


















                
