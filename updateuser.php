<?php 

require_once("header.php"); 

if(isset($_GET['id']))
{
  $user = User::get_by_id($_GET['id']);
}
else
{
  header("location: index.php?negative");
}

if(!$session->is_logged_in())
{
  header("location: index.php?negative");
}
else
{
  $loggeduser = User::get_by_id($session->userid);

  if($loggeduser->enabled == DISABLED)
  {
    header("location: index.php?disabled");
  }
}

$pathinfo = pathinfo($_SERVER["PHP_SELF"]);
$basename = $pathinfo["basename"];
$currentFile = str_replace(".php","", $basename);

?>

<div class="container-fluid">
<div class="row-fluid">
  <div class="span1"></div>
  <div class="span9">
    <form id="theform" class="form-horizontal" action="#" method="post" enctype="multipart/form-data">
      <fieldset>
      <legend>
        Update
      </legend>

      <div class="control-group">
          <label class="control-label" for="moto">Photo</label>
          <div class="controls">
            <div class="fileupload fileupload-new" data-provides="fileupload">
              <div class="fileupload-new thumbnail" style="height: 200px;"><img src='data:image/jpeg;base64, <?php echo $user->picture; ?>' /></div>
              <div class="fileupload-preview fileupload-exists thumbnail" style="max-height: 200px; line-height: 20px;"></div>
              <div>
                <span class="btn btn-file">
                  <span class="fileupload-new">Select image</span>
                  <span class="fileupload-exists">Change</span>
                  <input name="MAX_FILE_SIZE" hidden value="2097152" />
                  <input name="picture" type="file" />
                </span>
                <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                <a class="mytooltip" data-toggle="tooltip" data-placement="right" 
                  title=
                  "
                    OPTIONAL: extensions allowed: JPEG/JPG and PNG
                    , Up to 2MB, Recommended size: 200x200
                  ">
                  <span class="label label">?</span>
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="username">* Username</label>
          <div class="controls">
            <input value="<?php echo $user->username; ?>" id="username" name="username" type="text" placeholder="username" class="input-xlarge">
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="password">* Password</label>
          <div class="controls">
            <input id="password" name="password" type="password" placeholder="password" value="<?php echo $user->password; ?>" class="input-xlarge span4">
            <a class="btn btn-small" onclick="generate(); return false;">Generate</a>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="email">* Email</label>
          <div class="controls">
            <input value="<?php echo $user->email; ?>" id="email" name="email" type="email" placeholder="email" class="input-xlarge">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="firstname">* First Name</label>
          <div class="controls">
            <input value="<?php echo $user->firstname; ?>" id="firstname" name="firstname" type="text" placeholder="first name" class="input-xlarge">
          </div>
        </div>
        
        <div class="control-group">
          <label class="control-label" for="lastname">* Last Name</label>
          <div class="controls">
            <input value="<?php echo $user->lastname; ?>" id="lastname" name="lastname" type="text" placeholder="last name" class="input-xlarge">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="middlename">Middle Name</label>
          <div class="controls">
            <input value="<?php echo $user->middlename; ?>" id="middlename" name="middlename" type="text" placeholder="middle name" class="input-xlarge">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="moto">Birth Date</label>
          <div class="controls">
            <div class="input-append date" id="dp3" data-date="<?php echo $user->birthdate; ?>" data-date-format="yyyy-mm-dd">
              <input  value="<?php echo $user->birthdate; ?>" name="birthdate" class="span7" size="63" type="text">
              <span class="add-on"><i class="icon-th"></i></span>
            </div>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="gender">Gender</label>
          <div class="controls">
            <input type="hidden" name="gender" value="<?php echo $user->gender; ?>" id="btn-input1" />
            <div class="btn-group" data-toggle="buttons-radio">
              <button type="button" value="1" id="btn-enabled1" class="btn active">Male</button>
              <button type="button" value="2" id="btn-disabled1" class="btn">Female</button>
            </div>
          </div>
        </div>

      <input type="hidden" name="userid" value="<?php echo $user->id; ?>" />

      <!-- Button -->
      <div class="control-group">
        <label class="control-label" for="updatesubmit"></label>
        <div class="controls">
          <button id="btnsave" name="btncreate" class="btn btn-primary">Save</button>
        </div>
      </div>

      </fieldset>
      </form>
  </div>
</div><!--/row-->
<script>

function generate()
{
  var keylist="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
  var password = "";

  for (var i = 0; i < 7; i++)
  {
    password += keylist.charAt(Math.floor(Math.random() * keylist.length));
  }

  bootbox.alert("<i>Copy the Generated Password:</i> <br /><br /> <h1>&nbsp;&nbsp;" + password + "</h1>");
}

$(function () 
{

  $("#btnsave").click(function()
  {
    var formData = new FormData($("#theform")[0]);

    $("#btnsave").text("Saving...");
    $("#btnsave").attr("disabled", "disabled");

    $.ajax(
    {
      type: 'POST',
      url: 'includes/webservices/updateuser.php',
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      xhr: function() 
      {
          var myXhr = $.ajaxSettings.xhr();

          if(myXhr.upload)
          {
              myXhr.upload.addEventListener('progress', progressHandlingFunction, false);
          }
          return myXhr;
      },
      success: function(result) 
      {
        if(result == "success")
        {
          showToast("Successfully Saved", "success");
          $("#btnsave").text("Save");
          $("#btnsave").removeAttr("disabled");
        }
        else
        {
          bootbox.alert(result);
          $("#btnsave").text("Save");
          $("#btnsave").removeAttr("disabled");
        }
      }
    });

    return false;
  });

  $(':file').change(function()
  {
      var file = this.files[0];
      name = file.name;
      size = file.size;
      type = file.type;
  });

  function progressHandlingFunction(e)
  {
    if(e.lengthComputable)
    {
      // $('.progress').attr({value:e.loaded,max:e.total});
      console.log("max: "+e.total+", progress: " + e.loaded);
    }
  }

  var btns = ['btn-enabled1', 'btn-disabled1'];
  var input = document.getElementById('btn-input1');

  for(var i = 0; i < btns.length; i++) 
  {
    document.getElementById(btns[i]).addEventListener('click', function() 
    {
      input.value = this.value;
    });
  }

});

</script>
      
<?php require_once("footer.php"); ?>