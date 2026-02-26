<?php


if (move_uploaded_file($_FILES['image']['tmp_name'], 'image/' . $_FILES['image']['name'])) {
    echo 'Successfully Uploaded!';
} else {
    echo 'Error in file upload.';
}

?>