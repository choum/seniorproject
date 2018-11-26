<?php
    include '../student-controller.php';
?>

<div class="card-body" style="padding-top: 0px;" id="upload-form">
    <?php
    if(!empty($courses[0])){
        ?>
        <div id="upload-messages"></div>
        <form id="assignment-form">
            <div class="form-group">
                <label>Class:</label><br/>
                <select name="user-courses" id="user-courses">
                    <option value="">Select Course...</option>
                    <?php
                    foreach($courses as $course){
                        echo '<option value="'.$course->courseID.'">'.$course->courseTitle.'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Assignment Name</label><br/>
                <select name="user-assignments" id="user-assignments">
                    <option id="choose-course" value="">Select a course first</option>
                </select>
            </div>
            <div class="form-group">
                <label for="zip">Project Files:</label>
                <input type="file" class="form-control-file" name="zip" id="zip"
                       aria-describedby="fileHelp">
            </div>
            <div class="form-group">
                <label for="filesToUpload[]">Images for Carousel Display:</label><br/>
                <input name="filesToUpload[]" class="form-control-file" id="filesToUpload" type="file"
                       multiple=""/><br/>
            </div>
            <div class="form-group">
                <p>Make this your featured assignment? <input type="checkbox" name="featured" value=""></p>
            </div>
            <div class="form-group">
                <p>Is this a group project? <input type="checkbox" name="group" value=""></p>
            </div>
            <input type='hidden' name='action' value='upload_assignment'>
            <div class="form-group">
                <button type="submit" class="btn" value="Upload">Upload</button>
            </div>

        </form>
        <hr/>
    <?php }
    else{
        ?>
        <p>You may not upload files until you register for a course.</p>
        <?php

    }
    ?>
</div>