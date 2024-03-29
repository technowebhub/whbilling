<?php
// *************************************************************************
// *                                                                       *
// * iBilling -  Accounting, Billing Software                              *
// * Copyright (c) Sadia Sharmin. All Rights Reserved                      *
// *                                                                       *
// *************************************************************************
// *                                                                       *
// * Email: sadiasharmin3139@gmail.com                                                *
// * Website: http://www.sadiasharmin.com                                  *
// *                                                                       *
// *************************************************************************
// *                                                                       *
// * This software is furnished under a license and may be used and copied *
// * only  in  accordance  with  the  terms  of such  license and with the *
// * inclusion of the above copyright notice.                              *
// * If you Purchased from Codecanyon, Please read the full License from   *
// * here- http://codecanyon.net/licenses/standard                         *
// *                                                                       *
// *************************************************************************
_auth();
$ui->assign('_title', 'Utilities- '. $config['CompanyName']);
$ui->assign('_st', 'Utilities');
$ui->assign('_sysfrm_menu', 'util');
$action = $routes['1'];
$user = User::_info();
$ui->assign('user', $user);
switch ($action) {

    case 'activity':
        $cnt = ORM::for_table('sys_logs')->count();
        $ui->assign('cnt',$cnt);

        $ui->assign('xheader', '
<link rel="stylesheet" type="text/css" href="'.APP_URL.'/ui/lib/dt/media/css/jquery.dataTables.min.css"/>
');
        $ui->assign('xfooter', '
<script type="text/javascript" src="'.APP_URL.'/ui/lib/dt/media/js/jquery.dataTables.min.js"></script>
');
        $ui->assign('xjq', '
          var dt =  $(\'#sys_logs\').dataTable( {
		"processing": true,
		"serverSide": true,
		 responsive: true,
		 "iDisplayLength": 50,
		 "order": [[ 0, "desc" ]],
		"ajax": "'.APP_URL.'/index.php?_route=util/ajax_logs",
		 dom: \'T<"clear">firtp\'



	} );

	 $("#clear_logs").click(function (e) {
        e.preventDefault();
        bootbox.confirm("This will delete all logs older than 30 days. Are you sure?", function(result) {
           if(result){
               var _url = $("#_url").val();
               $.get( _url+"util/clear_logs", function( data ) {
location.reload();
});
           }
        });
    });

 ');

        $ui->display('util-activity.tpl');
        break;

    case 'ajax_logs':

        $table = 'sys_logs';

// Table's primary key
        $primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
        $df = $config['df'].' H:i:s';

        $columns = array(
            array( 'db' => 'id', 'dt' => 0 ),
            array(
                'db'        => 'date',
                'dt'        => 1,
                'formatter' => function( $d, $row) {
                    global $df;
                     return date( $df, strtotime($d));


                }
            ),
            array( 'db' => 'type',   'dt' => 2 ),
            array( 'db' => 'description',     'dt' => 3 ),
            array( 'db' => 'userid',     'dt' => 4 ),
            array( 'db' => 'ip',     'dt' => 5 ),

        );

// SQL server connection information
        $sql_details = array(
            'user' => $db_user,
            'pass' => $db_password,
            'db'   => $db_name,
            'host' => $db_host
        );


        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * If you just want to use the basic configuration for DataTables with PHP
         * server-side, there is no need to edit below this line.
         */

        // require( 'ssp.class.php' );

        $x= json_encode(
            Ssp::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
        );
        //  Dev::_log($x);
        echo $x;

        break;

    case 'clear_logs':
        $b30 = date('Y-m-d H:i:s',strtotime('-30 days', time()));
        $d = ORM::for_table('sys_logs')
            ->where_lte('date', $b30)
            ->delete_many();
        _msglog('s','Logs older than 30 days has been deleted');

        break;


    case 'sent-emails':
        $cnt = ORM::for_table('sys_email_logs')->count();
        $ui->assign('cnt',$cnt);

        $ui->assign('xheader', '
<link rel="stylesheet" type="text/css" href="'.APP_URL.'/ui/lib/dt/media/css/jquery.dataTables.min.css"/>
');
        $ui->assign('xfooter', '
<script type="text/javascript" src="'.APP_URL.'/ui/lib/dt/media/js/jquery.dataTables.min.js"></script>
');
        $ui->assign('xjq', '
          var dt =  $(\'#sys_logs\').dataTable( {
		"processing": true,
		"serverSide": true,
		 responsive: true,
		 "iDisplayLength": 50,
		 "order": [[ 0, "desc" ]],
		"ajax": "'.APP_URL.'/index.php?_route=util/ajax_sent-emails/",
		 dom: \'T<"clear">firtp\'



	} );

	 $("#clear_logs").click(function (e) {
        e.preventDefault();
        bootbox.confirm("This will delete all logs older than 30 days. Are you sure?", function(result) {
           if(result){
               var _url = $("#_url").val();
               $.get( _url+ "util/clear_logs/", function( data ) {
location.reload();
});
           }
        });
    });

 ');

        $ui->display('util-sent-emails.tpl');
        break;

    case 'ajax_sent-emails':

        $table = 'sys_email_logs';
        $df = $config['df'].' H:i:s';
// Table's primary key
        $primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
        $columns = array(
            array( 'db' => 'id', 'dt' => 0 ),
            array(
                'db'        => 'date',
                'dt'        => 1,
                'formatter' => function( $d, $row) {
                    global $df;
                    return date( $df, strtotime($d));


                }
            ),

            array( 'db' => 'email',     'dt' => 2 ),
            array( 'db' => 'subject',     'dt' => 3 ),
            array(
                'db'        => 'id',
                'dt'        => 4,
                'formatter' => function( $d, $row ) {
                    //  return date( 'jS M y', strtotime($d));
                    //
                    return '<a href="'.U.'util/view-email/'.$d.'/" class="btn btn-primary btn-outline btn-xs"><i class="fa fa-envelope-o"></i> View</a>';
                }
            )

        );

// SQL server connection information
        $sql_details = array(
            'user' => $db_user,
            'pass' => $db_password,
            'db'   => $db_name,
            'host' => $db_host
        );


        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * If you just want to use the basic configuration for DataTables with PHP
         * server-side, there is no need to edit below this line.
         */

        // require( 'ssp.class.php' );

        $x= json_encode(
            Ssp::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
        );
        //  Dev::_log($x);
        header('Content-Type: application/json');
        echo $x;

        break;




    case 'dbstatus':
        $dbc = new mysqli($db_host,$db_user ,$db_password,$db_name);
        if ($result = $dbc->query('SHOW TABLE STATUS')) {
            $size = 0;
            $decimals = 2;
            $tables = array();
            while($row = $result->fetch_array()){
                $size += $row["Data_length"] + $row["Index_length"];
                $total_size = ($row[ "Data_length" ] + $row[ "Index_length" ]) / 1024;
                $tables[$row['Name']]['size'] = number_format($total_size,'0');
                $tables[$row['Name']]['rows'] = $row[ "Rows" ];
                $tables[$row['Name']]['name'] = $row[ "Name" ];
            }

            $mbytes = number_format($size/(1024*1024),$decimals);

           $ui->assign('tables',$tables);
           $ui->assign('dbsize',$mbytes);
            $ui->display('dbstatus.tpl');

        }
        break;

    case 'dbbackup':

        try {
            // open the connection to the database - $host, $user, $password, $database should already be set
            $mysqli = new mysqli($db_host,$db_user ,$db_password,$db_name);

            // did it work?
            if ($mysqli->connect_errno) {
                throw new Exception("Failed to connect to MySQL: " . $mysqli->connect_error);
            }

            header('Pragma: public');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Content-Type: application/force-download');
            header('Content-Type: application/octet-stream');
            header('Content-Type: application/download');
            header('Content-Disposition: attachment;filename="backup_'.date('Y-m-d_h_i_s') . '.sql"');
            header('Content-Transfer-Encoding: binary');

            // start buffering output
            // it is not clear to me whether this needs to be done since the headers have already been set.
            // However in the PHP 'header' documentation (http://php.net/manual/en/function.header.php) it says that "Headers will only be accessible and output when a SAPI that supports them is in use."
            // rather than the possibility of falling through a real time window there seems to be no problem buffering the output anyway
            ob_start();
            $f_output = fopen("php://output", 'w');

            // put a few comments into the SQL file
            print("-- pjl SQL Dump\n");
            print("-- Server version:".$mysqli->server_info."\n");
            print("-- Generated: ".date('Y-m-d h:i:s')."\n");
            print('-- Current PHP version: '.phpversion()."\n");
            print('-- Host: '.$db_host."\n");
            print('-- Database:'.$db_name."\n");

            //get a list of all the tables
            $aTables = array();
            $strSQL = 'SHOW TABLES';            // I put the SQL into a variable for debuggin purposes - better that "check syntax near '), "
            if (!$res_tables = $mysqli->query($strSQL))
                throw new Exception("MySQL Error: " . $mysqli->error . 'SQL: '.$strSQL);

            while($row = $res_tables->fetch_array()) {
                $aTables[] = $row[0];
            }

            // Don't really need to do this (unless there is loads of data) since PHP will tidy up for us but I think it is better not to be sloppy
            // I don't do this at the end in case there is an Exception
            $res_tables->free();

            //now go through all the tables in the database
            foreach($aTables as $table)
            {
                print("-- --------------------------------------------------------\n");
                print("-- Structure for '". $table."'\n");
                print("--\n\n");

                // remove the table if it exists
              //  print('DROP TABLE IF EXISTS '.$table.';');

                // ask MySQL how to create the table
                $strSQL = 'SHOW CREATE TABLE '.$table;
                if (!$res_create = $mysqli->query($strSQL))
                    throw new Exception("MySQL Error: " . $mysqli->error . 'SQL: '.$strSQL);
                $row_create = $res_create->fetch_assoc();

                print("\n".$row_create['Create Table'].";\n");


                print("-- --------------------------------------------------------\n");
                print('-- Dump Data for `'. $table."`\n");
                print("--\n\n");
                $res_create->free();

                // get the data from the table
                $strSQL = 'SELECT * FROM '.$table;
                if (!$res_select = $mysqli->query($strSQL))
                    throw new Exception("MySQL Error: " . $mysqli->error . 'SQL: '.$strSQL);

                // get information about the fields
                $fields_info = $res_select->fetch_fields();

                // now we can go through every field/value pair.
                // for each field/value we build a string strFields/strValues
                while ($values = $res_select->fetch_assoc()) {

                    $strFields = '';
                    $strValues = '';
                    foreach ($fields_info as $field) {
                        if ($strFields != '') $strFields .= ',';
                        $strFields .= "`".$field->name."`";

                        // put quotes round everything - MYSQL will do type convertion (I hope) - also strip out any nasty characters
                        if ($strValues != '') $strValues .= ',';
                        $strValues .= '"'.preg_replace('/[^(\x20-\x7F)\x0A]*/','',$values[$field->name].'"');
                    }

                    // now we can put the values/fields into the insert command.
                    print("INSERT INTO ".$table." (".$strFields.") VALUES (".$strValues.");\n");
                }
                print("\n\n\n");

                $res_select->free();

            }


        } catch (Exception $e) {
            print($e->getMessage());
        }


        fclose($f_output);
        print(ob_get_clean());
        $mysqli->close();

        break;


    case 'view-email':

        $id = $routes['2'];

        $d = ORM::for_table('sys_email_logs')->find_one($id);
        if($d){

            $ui->assign('d',$d);
$ui->display('view-email.tpl');

        }
        else{

        }


        break;

    default:
        echo 'action not defined';
}