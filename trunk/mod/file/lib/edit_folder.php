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

if ($this_folder != 0) {

    $folder_details = get_record('file_folders','ident',$this_folder);
    if (!empty($folder)  && (permissions_check("files:edit",$folder_details->owner) || permissions_check("files:edit",$folder_details->files_owner))) {
        // $edit = __gettext("Edit this folder"); // gettext variable
        $run_result .= <<< END
    <form action="" method="post">
END;
        $labelValue = __gettext("Folder name:"); // gettext variable
        $parentFolder = __gettext("Parent folder:"); // gettext variable
        $folderType = __gettext("Folder type:"); // gettext variable
        $folderTypePicker = file_folder_type_switcher($folder_details, "edit_folder_type");
        $body = <<< END
        <table width="100%">
            <tr>
                <td width="30%">
                    <p><label for="new_folder_name">
                        $labelValue
                    </label></p>
                </td>
                <td>
                    <p><input type="text" name="edit_folder_name" id="edit_folder_name" value="{$folder_details->name}" /></p>
                </td>
            </tr>
            <!--tr>
                <td>
                    <!--p><label for="new_folder_type">$folderType</label></p-->
                </td>
                <td>
                    <!--p>$folderTypePicker</p-->
                </td>
            </tr-->
            <tr>
                <td>
                    <p><label for="edit_folder_parent">
                        $parentFolder
                    </label></p>
                </td>
                <td><p>
END;
        // $body .= run("folder:select", array("edit_folder_parent",$_SESSION['userid'],$folder_details->parent));
        $body .= run("folder:select", array("edit_folder_parent",$folder_details->files_owner,$folder_details->parent));
        $accessLabel = __gettext("Access restrictions:"); // gettext variable
        $body .= <<< END
                </p></td>
            </tr>
            <tr>
                <td><p>
                    <label for="edit_folder_access">
                        $accessLabel
                    </label></p>
                </td>
                <td><p>
END;



        $body .= run("display:access_level_select",array("edit_folder_access",$folder_details->access));
        $keywords = __gettext("Keywords (comma separated):"); // gettext variable
        $body .= <<< END
                </p></td>
            </tr>
            <tr>
                <td><p>
                    <label for="edit_folder_access">
                        $keywords
                    </label>
                </td></p>
                <td><p>
END;
        $body .= display_input_field(array("edit_folder_keywords","","keywords","folder",$this_folder));
        $save = __gettext("Save"); // gettext variable
        $body .= <<< END
                </p></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><p>
                    <input type="hidden" name="action" value="edit_folder" />
                    <input type="hidden" name="edit_folder_id" value="{$this_folder}" />
                    <input type="submit" value=$save /></p>
                </td>
            </tr>
        </table>
    </form>
END;
        $title = __gettext("Edit this folder");
        $run_result .= templates_draw(array(
                                            'context' => 'databoxvertical',
                                            'name' => $title,
                                            'contents' => $body
                                            )
                                      );
    }
}

/*$header = __gettext("Upload files and folders");//gettext variable
$run_result .= <<< END
    <h4>
        <a name="addFile"></a>$header
    </h4>
    <form action="" method="post">
END;

$title = __gettext("Create a new folder");
$createLabel = __gettext("To create a new folder, enter its name:"); //gettext variable
$accessLabel = __gettext("Access restrictions:"); //gettext variable
$folderType = __gettext("Folder type:"); // gettext variable
$folderTypePicker = file_folder_type_switcher(null, "edit_folder_type");

$body = <<< END
        <table>
            <tr>
                <td width="30%"><p>
                    <label for="new_folder_name">
                        $createLabel
                    </label></p>
                </td>
                <td><p>
                    <input type="text" name="new_folder_name" id="new_folder_name" value="" />
                    </p>
                </td>
            </tr>

            <tr>
                <td><p>
                    <label for="new_folder_access">
                        $accessLabel
                    </label>
                    </p>
                </td>
                <td><p>
END;
$body .= run("display:access_level_select",array("new_folder_access",default_access));
$keywords = __gettext("Keywords (comma separated):"); // gettext variable
$body .= <<< END
                </p></td>
            </tr>
            <tr>
                <td><p>
                    <label for="new_folder_keywords">
                        $keywords
                    </label>
                </p></td>
                <td><p>
END;
$body .= display_input_field(array("new_folder_keywords","","keywords","folder"));
$create = __gettext("Create"); // gettext variable
$body .= <<< END
                </p></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><p>
                    <input type="hidden" name="folder" value="{$folder}" />
                    <input type="hidden" name="files_owner" value="{$page_owner}" />
                    <input type="hidden" name="action" value="files:createfolder" />
                    <input type="submit" value=$create /></p>
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
END;*/

?>