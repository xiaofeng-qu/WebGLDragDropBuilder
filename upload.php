<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="js/jquery.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
        <script src="js/jquery-ui.js"></script>
        <script src="js/gl-matrix.js"></script>
        <script src="js/ATextureCube.js"></script>
        <!-- Optional JavaScript -->
        <script>
            function drop_handler(canvas, ev){
                var rect = canvas.getBoundingClientRect();
                var imageSrc = ev.dataTransfer.getData('URL');
                x = ev.clientX - rect.left;
                y = ev.clientY - rect.top;
                console.log("Photo url is: " + imageSrc + "; x postion is: " + x + "; y position is: " + y + ".");
                dragImgToCanvas(imageSrc);
            }
            function dragover_handler(ev){
                ev.preventDefault();
                return false;
            }
            function dragImgToCanvas(imageSrc){
                document.getElementById("crate-image").src = imageSrc;
                InitDemo();
            }
        </script>
    </head>
    <body onload = "InitDemo();">
        <div class="container" style="margin-top: 25px;">
            <img id='crate-image'  width ="0" height ="0" style="display: none;"/>
            <?php
                $target_dir = "uploads/";
                $target_file = [];
                $errors = '';
                for($i=0; $i<count($_FILES['texture']['name']); $i++){
                    $target_file[] = $target_dir . basename($_FILES["texture"]["name"][$i]);
                    $type = mime_content_type($_FILES["texture"]["tmp_name"][$i]);
                    if(!strstr($type, 'image/')) {
                        $errors .= '<p>' . basename($_FILES["texture"]["name"][$i]) . ' is not an image.</p>';
                    }
                }
                if($errors != ''){
                    echo '<div class="contianer"><div class="jumbotron text-center alert-danger"><h1>Some errors happened</h1>';
                    echo $errors;
                    echo '<a class="btn btn-danger" href="index.html">Upload your texture again</a>'; 
                    echo '</div></div>';
                }else{
                    for($i=0; $i<count($_FILES['texture']['name']); $i++){
                        if(file_exists($target_file[$i])){
                        unlink($target_file[$i]);
                        }
                        move_uploaded_file($_FILES["texture"]["tmp_name"][$i], $target_file[$i]);
                    }
                    echo '<div class="container"><div class="row">';
                    echo '<div class="col-sm-12"><h1>Your textures:</h1></div>';
                    for($i=0; $i<count($_FILES['texture']['name']); $i++){
                        echo '<div class="col-sm-3"><img src="' . $target_file[$i] . '" class="img-thumbnail" style="width:100%; height: auto;"></div>';
                    }
                    ?><div class="container" id="newpage" style="margin-top: 15px; margin-bottom: 10px; height: 500px">
                        <canvas id="aCanvas" style="border:1px solid #000; margin-top: 15px;" width = "800" height = "500" ondrop="drop_handler(this, event);" ondragover="dragover_handler(event);">Your browser does not support the HTML5 canvas tag.</canvas>
                    </div><?php
                    echo '</div></div>';
                }?>
        </div>
    </body>
</html>