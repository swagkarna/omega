<?

//            ---------------------------------------------------
//                              Omega Framework                                
//            ---------------------------------------------------
//                  Copyright (C) <2020>  <Entynetproject>       
//
//        This program is free software: you can redistribute it and/or modify
//        it under the terms of the GNU General Public License as published by
//        the Free Software Foundation, either version 3 of the License, or
//        any later version.
//
//        This program is distributed in the hope that it will be useful,
//        but WITHOUT ANY WARRANTY; without even the implied warranty of
//        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
//        GNU General Public License for more details.
//
//        You should have received a copy of the GNU General Public License
//        along with this program.  If not, see <http://www.gnu.org/licenses/>.

!import(execute);
!import(dirAccess);


// determine temporary directory.
function get_tmp_dir()
{
    if (function_exists('sys_get_temp_dir'))
        $t = @sys_get_temp_dir();
    else
    {
        if (!empty($_ENV['TMP']))
            $t = $_ENV['TMP'];
        elseif (!empty($_ENV['TMPDIR']))
            $t = $_ENV['TMPDIR'];
        elseif (!empty($_ENV['TEMP']))
            $t = $_ENV['TEMP'];
        else
        {
            $t = @tempnam(":\n\\/?><", "dkdk");
            if (@file_exists($t))
                @unlink($t);
            $t = @dirname($t);
        }
    }
    return ($t);
}


// $R:
// raw array of variables returned by the connector.
// This array shall contain enough informations to
// determine tunnel environment variables that
// will be used by omega for the created tunnel.
$R = $_SERVER;

$R['PHP_OS'] = PHP_OS;
$R['PHP_VERSION'] = PHP_VERSION;
$R['WHOAMI'] = @execute('whoami');

$tmp = @execute('echo $HOME');
$R['HOME'] = ($tmp != '$HOME' && $tmp) ? $tmp : '';

// Determine WEB_ROOT environment variable
$R['WEB_ROOT'] = $R['DOCUMENT_ROOT'];
if (!$R['WEB_ROOT'])
    $R['WEB_ROOT'] = $R['APPL_PHYSICAL_PATH'];

if (!$R['WEB_ROOT'])
{
    $rel = $R['SCRIPT_NAME'];
    $abs = $R['SCRIPT_FILENAME'];
    if (!$rel || !$abs)
    {
        $rel = $R['PATH_INFO'];
        $abs = $R['PATH_TRANSLATED'];
    }
    if ($rel && $abs)
    {
        foreach (Array("\\\\", "\\", "/") as $sep)
        {
            $tmp = str_replace("/", $sep, $rel);
            $len = strlen($tmp);
            if($tmp == substr($abs, -$len))
                $R['WEB_ROOT'] = substr($abs, 0, -$len);
        }
    }
}

if (($x = @realpath($R['WEB_ROOT'])) !== False)
    $R['WEB_ROOT'] = $x;


// Determine WRITEABLE_WEBDIR.
// This code recursively browses subdirectories,
// starting with the WEB_ROOT, and searching for the
// writeable path which is the closest to WER_BOOT.
$MAX_RECURSION = 6;
$dir_list = Array($R['WEB_ROOT']);
$R['WRITEABLE_WEBDIR'] = '';
for ($recursion = 1; $recursion <= $MAX_RECURSION; $recursion++)
{
    foreach ($dir_list as $dir)
    {
        if (dirAccess($dir, 'w')){
            $R['WRITEABLE_WEBDIR'] = @realpath($dir);
            break (2);
        }
    }
    if ($recursion == $MAX_RECURSION)
        break;
    $parent_dir_list = $dir_list;
    $dir_list = Array();
    foreach ($parent_dir_list as $dir)
    {
        if ($h = @opendir($dir))
        {
            while (($elem = readdir($h)) !== FALSE)
            {
                if ($elem != '.' && $elem != '..')
                {
                    if ((@fileperms($dir.'/'.$elem) & 0x4000) == 0x4000)
                    {
                        $dir_list[]=$dir.'/'.$elem;
                    }
                }
            }
            closedir($h);
        }
    }
}


$tmp = get_tmp_dir();
$R['WRITEABLE_TMPDIR'] = (dirAccess($tmp,'w')) ? $tmp : $R['WRITEABLE_WEBDIR'];


return $R;

?>
