<?php

// Lets check to see if we have a database or not and give a decent error message
try {
    define('PDO_WILL_CATCH', true);
    require_once("pdo.php");
} catch(PDOException $ex){
    $msg = $ex->getMessage();
    error_log("DB connection: "+$msg);
    echo('<div class="alert alert-danger" style="margin: 10px;">'."\n");
    if ( strpos($msg, 'Unknown database') !== false ) {
        echo("<p>It does not appear as though your database exists.</p>
<p> If you have full access to your MySql instance (i.e. like 
MAMP or XAMPP, you may need to run commands like this:</p>
<pre>
    CREATE DATABASE mmorps DEFAULT CHARACTER SET utf8;
    GRANT ALL ON mmorps.* TO 'mmouser'@'localhost' IDENTIFIED BY 'mmopassword';
    GRANT ALL ON mmorps.* TO 'mmouser'@'127.0.0.1' IDENTIFIED BY 'mmopassword';
</pre>
<p>Make sure to choose appropriate passwords when setting this up.</p>
<p>If you are running in a hosted environment and are using an admin tool like
CPanel (or equivalent).  You must user this interface to create a database, 
user, and password.</p>
<p>
In some systems, a database adminstrator will create the database,
user, and password and simply give them to you.
<p>
Once you have the database, account and password you must update your
<code>config.php</code> with this information.</p>
");
    } else if ( strpos($msg, 'Access denied for user') !== false ) {
        echo('<p>It appears that you are unable to access 
your database due to a problem with the user and password.
The user and password for the database conneciton are setup using either a 
SQL <code>GRANT</code> command or created in an adminstration tool like CPanel.
Here are sample commands to set up a database:'."
<pre>
    CREATE DATABASE mmorps DEFAULT CHARACTER SET utf8;
    GRANT ALL ON mmorps.* TO 'mmouser'@'localhost' IDENTIFIED BY 'mmopassword';
    GRANT ALL ON mmorps.* TO 'mmouser'@'127.0.0.1' IDENTIFIED BY 'mmopassword';
</pre>".'
Or perhaps a system administrator created the database and gave you the
account and password to access the database.</p>
<p>Make sure to check the values in your <code>config.php</code> for 
<pre>
    $CFG->dbuser    = \'mmouser\';
    $CFG->dbpass    = \'mmopassword\';
</pre>
To make sure they match the account and password assigned to your database.
</p>
');
    } else if ( strpos($msg, 'Can\'t connect to MySQL server') !== false ) {
        echo('<p>It appears that you cannot connect to your MySQL server at 
all.  The most likely problem is the wrong host or port in this option 
in your <code>config.php</code> file:
<pre>
$CFG->pdo       = \'mysql:host=127.0.0.1;dbname=mmorps\';
# $CFG->pdo       = \'mysql:host=127.0.0.1;port=8889;dbname=mmorps\'; // MAMP
</pre>
The host may be incorrect - you might try switching from \'127.0.0.1\' to 
\'localhost\'.   Or if you are on a hosted system with an ISP the name of the 
database host might be given to you like \'db4263.mysql.1and1.com\' and you 
need to put that host name in the PDO string.</p>
<p>
Most systems are configured to use the default MySQL port of 3306 and if you 
omit "port=" in the PDO string it assumes 3306.  If you are using MAMP
this is usually moved to port 8889.  If neither 3306 nor 8889 works you
probably have a bad host name.  Or talk to your system administrator.
</p>
');
    } else {
        echo("<p>There is a problem with your database connection.</p>\n");
    }

    echo("<p>Database error detail: ".$msg."</p>\n");
    echo("<p>Once you have fixed the problem, come back to this page and refresh
to see if this message goes away.</p>");
    echo('<p>Installation instructions are avaiable at 
<a href="https://github.com/csev/mmorps"
target="_blank">https://github.com/csev/mmorps</a>');

    echo("\n</div>\n");
    die();
}   


// Now check the plugins table to see if it exists
$p = $CFG->dbprefix;
$plugins = "{$p}lms_plugins";
$table_fields = pdoMetadata($pdo, $plugins);
if ( $table_fields === false ) {
    echo('<div class="alert alert-danger" style="margin: 10px;">'."\n");
    echo("<p>It appears that your database connection is working properly
but you have no tables in your database.  To create the initial tables
needed for this application, use the 'Admin' feature.  You will be prompted
for the administrator master password as configured in <code>config.php</code>
in the <code>\$CFG->adminpw</code> setting.
</p>
");
    echo("\n</div>\n");
}   
