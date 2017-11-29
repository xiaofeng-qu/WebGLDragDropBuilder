<?php
    session_start();
    include('connection.php');
    if(!isset($_SESSION['user_id'])){
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>WebGL Arts Maker</title>
        <!-- Bootstrap -->
        <link href="css/bootstrap.css" rel="stylesheet">
        <!-- Customized style -->
        <link href="css/style.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
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
      <!--Navigation Bar-->
      <nav role="navigation" class="navbar navbar-default navbar-fixed-top">
          <div class="container-fluid">
              <div class="navbar-header">
                  <a href="index.php" class="navbar-brand">WebGL</a>
                  <button type="button" class="navbar-toggle" data-target="#navbarCollapse" data-toggle="collapse">
                      <span class="sr-only">Toggle Navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                  </button>
              </div>
              <div class="navbar-collapse collapse" id="navbarCollapse">
                  <ul class="nav navbar-nav">
                      <li><a href="prj.html">Cover</a></li>
                      <li><a href="landing.php">Uploads</a></li>
                      <li class="active"><a href="upload.php">Start designing</a></li>
                      <li><a href="help.html">Help</a></li>
                      <li><a href="technical.html">Technical</a></li>
                  </ul>
                  <ul class="nav navbar-nav navbar-right">
                      <li><a href="profile.php">Logged in as <b><?php echo $_SESSION['username'];?></b></a></li>
                      <li><a href="index.php?logout=1">Log out</a></li>
                  </ul>
              </div>
          </div>
      </nav>
      <div class="container" style="margin-top: 50px;">
            <img id='crate-image'  width ="0" height ="0" style="display: none;"/>
            <?php
                $target_dir = "uploads/";
                $target_file = [];
                $errors = '';
                $user_id = $_SESSION['user_id'];
                if(isset($_FILES['texture'])){
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
                            $sql = "SELECT * FROM pictures WHERE picture_url = '$target_file[$i]'";
                            $result = mysqli_query($link, $sql);
                            $count = mysqli_num_rows($result);
                            if($count == 0){
                                $sql = "INSERT INTO pictures (user_id, picture_url) VALUES ('$user_id', '$target_file[$i]')";
                                mysqli_query($link, $sql);
                            }
                        }
                    }
                }
                echo '<div class="container"><div class="row">';
                echo '<div class="col-sm-12"><h1>Your textures:</h1></div>';
                $sql = "SELECT picture_id, picture_url FROM pictures WHERE user_id = '$user_id'";
                $result = mysqli_query($link, $sql);
                $rowcount = mysqli_num_rows($result);
                if($rowcount == 0){
                    echo "You have no texture. Upload some.";
                }
                else{
                    for($i=1; $i<=$rowcount; $i++){
                        $row = mysqli_fetch_assoc($result);
                        echo '<div class="col-sm-3"><button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button><img src="' . $row['picture_url'] . '" class="img-thumbnail" style="width:100%; height: auto;" id=' . $row['picture_id'] . '></div>';
                    }
                }
                echo "</div>";
                ?><div class="container" id="newpage" style="margin-top: 15px; margin-bottom: 75px; height: 500px">
                    <canvas id="aCanvas" style="border:1px solid #000; margin-top: 15px;" width = "800" height = "500" ondrop="drop_handler(this, event);" ondragover="dragover_handler(event);">Your browser does not support the HTML5 canvas tag.</canvas>
                </div><?php
                echo '</div></div>';
                ?>
          </div>
      <!--Footer-->
      <div class="footer">
          <div class="container">
              <p>Xiaofeng & Bryce Copyright &copy; 2017-<?php echo date("Y")?>.</p>
          </div>
      </div>

      <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
      <!-- Include all compiled plugins (below), or include individual files as needed -->
      <script src="js/bootstrap.min.js"></script>
      <script src="js/gl-matrix.js"></script>
      <script src="js/ATextureCube.js"></script>
      <script src="js/mytexture.js"></script>
</body>
</html>