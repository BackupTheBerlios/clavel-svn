<?php

global $CFG;
global $folder;
global $page_owner;
global $folder_name;

// global $folder is current working directory
// global $folder_name is current working directory name, but only
// automatically set when viewed

$folder_name = htmlspecialchars($folder_name, ENT_COMPAT, 'utf-8');
$this_folder = optional_param('edit_folder_id',0,PARAM_INT);

$title = __gettext("Upload Photos");
$run_result .= <<< END
    <h4>
        <a name="addPhoto"></a>
    </h4>
END;

$run_result .= <<< END
    </form><br />
    <form action="{$CFG->wwwroot}mod/file/action_redirection.php" method="post" enctype="multipart/form-data">
END;


$body = <<< END

        <table>
            <tr>
                <td colspan="2"><p>
END;

$usedquota = get_field_sql('SELECT sum(size) FROM '.$CFG->prefix.'files WHERE files_owner = ?',array($page_owner));

// $totalquota = user_info('file_quota',$USER->ident);
$totalquota = user_info('file_quota',$page_owner);
if ($page_owner == $_SESSION['userid']) {
    $body .= sprintf(__gettext("You have used %s Mb of a total %s Mb."),round(($usedquota / 1000000),4),round(($totalquota / 1000000),4));
} else {
    $body .= sprintf(__gettext("Used space: %s Mb."),round(($usedquota / 1000000),4));
}
$fileLabel = __gettext("Photos to upload:"); //gettext variable
$fileTitle = __gettext("Title for Photo:"); //gettext variable
$fileDesc = __gettext("Photo description:"); //gettext variable
$fileAccess = __gettext("Access restrictions:"); //gettext variable
$body .= <<< END
                </p></td>
            <tr>
                <td width="30%"><p>
                    <label for="new_file">
                        $fileLabel
                    </label>
                </p></td>
                <td><p>
                        <input name="new_file1" id="new_file1" type="file" /><br />
                        <input name="new_file2" id="new_file1" type="file" /><br />
                        <input name="new_file3" id="new_file1" type="file" /><br />
                        <input name="new_file4" id="new_file1" type="file" /><br />
                        <input name="new_file5" id="new_file1" type="file" />
                </p></td>
            </tr>
            <tr>
                <td><p>
                    <label for="new_file_title">
                        $fileTitle
                    </label>
                    </p>
                </td>
                <td><p>
                    <input type="text" id="new_file_title" name="new_file_title" value="" />
                    </p>
                </td>
            </tr>

            <tr>
                <td><p>
                    <label for="new_file_description">
                        $fileDesc
                    </label>
                    </p>
                </td>
                <td><p>
                    <textarea id="new_file_description" name="new_file_description"></textarea>
                    </p>
                </td>
            </tr>
            <tr>
                <td><p>
                    <label for="new_file_access">
                        $fileAccess
                    </label>
                    </p>
                </td>
                <td><p>
END;
$body .= run("display:access_level_select",array("new_file_access",default_access));
$keywords = __gettext("Keywords (comma separated):"); // gettext variable
$body .= <<< END
                </p></td>
            </tr>
            <tr>
                <td><p>
                    <label for="new_file_keywords">
                        $keywords
                    </label>
                    </p>
                </td>
                <td><p>
END;
$body .= display_input_field(array("new_file_keywords","","keywords","file"));
$body .= <<< END
                    </p>
                </td>
            </tr>
END;

$body .= run("metadata:edit");

$copyright = __gettext("By checking this box, you are asserting that you have the legal right to share these uploaded files, and that you understand you are sharing it with other users of the system."); //gettext variable
$upload = __gettext("Upload"); //gettext variable
$body .= <<< END

            <tr>
                <td colspan="2"><p><label for="copyrightokcheckbox">
                    <input type="checkbox" id="copyrightokcheckbox" name="copyright" value="ok" />
                    $copyright
                    </label></p>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><br />
                    <input type="hidden" name="folder" value="{$folder}" />
                    <input type="hidden" name="files_owner" value="{$page_owner}" />
                    <input type="hidden" name="action" value="files:uploadfile" />
                    <input type="submit" value=$upload />
                </td>
            </tr>

        </table>
END;

$run_result .= templates_draw(array(
                                    'context' => 'databoxvertical',
                                    'name' => $title,
                                    'contents' => $body
                                    )
                              );

$run_result .= <<< END
    </form>
END;

?>