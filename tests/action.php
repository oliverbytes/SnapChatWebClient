<?php

require_once("../src/snapchat.php");

require_once("../includes/initialize.php");

ini_set('max_execution_time', 300);

const OKAY = "okay";
const BAD = "bad";

$response = new Response();
$response->message  = "";
$response->status   = "";

$action     = "";
$username   = "";
$auth_token = "";
$sortby     = "";
$sortorder  = "";
$showonly   = "";
$maxtoshow  = "";

if(isset($_GET['maxtoshow']))
{
  $maxtoshow = $_GET['maxtoshow'];
}

if(isset($_GET['showonly']))
{
  $showonly = $_GET['showonly'];
}

if(isset($_GET['sortby']))
{
  $sortby = $_GET['sortby'];
}

if(isset($_GET['action']))
{
  $action = $_GET['action'];

  if(isset($_POST['username']) && isset($_POST['auth_token']) || isset($_GET['username']) && isset($_GET['auth_token']))
  {
    if(isset($_POST['username']) && isset($_POST['auth_token']))
    {
      $username   = $_POST['username'];
      $auth_token = $_POST['auth_token'];
    }
    
    if(isset($_GET['username']) && isset($_GET['auth_token']))
    {
      $username   = $_GET['username'];
      $auth_token = $_GET['auth_token'];
    }

    $snapchat = new Snapchat();
    $snapchat->username = $username;
    $snapchat->auth_token = $auth_token;

    if(ExtendedProfile::get_by_username($snapchat->username) == false)
    {
      $object = new ExtendedProfile();
      $object->username = $snapchat->username;
      $object->create();
    }

    if($action == "getupdates")
    {
      $result = $snapchat->getUpdates2();

      formatSnaps($result, $snapchat);

      $response->status = OKAY;
      $response->message = $result;
    }
    if($action == "getcaptcha")
    {
      $result = $snapchat->getCaptcha();

      $response->status = OKAY;
      $response->message = $result;
    }
    if($action == "deletestory")
    {
      $result = $snapchat->deleteStory($_GET['storyid']);

      $response->status = OKAY;
      $response->message = $result;
    }
    else if($action == "logout")
    {
      $result = $snapchat->logout();

      $response->status = OKAY;
      $response->message = "Successfully Logged Out. :)";
    }
    else if($action == "download")
    {
      $mediaid    = $_GET['mediaid'];

      if(isset($_GET['mediatype'])) // support older version
      {
        $mediatype = $_GET['mediatype'];
      }
      else
      {
        $mediatype  = "photo";
      }

      $sender = "";

      if(isset($_GET['sender'])) // support older version
      {
        $sender = $_GET['sender'];
      }

      if($mediatype == "photo")
      {
        $filePath = 'media/opened/'.$snapchat->username."-".$sender."-".$mediaid.".jpg";
      }
      else if($mediatype == "video")
      {
        $filePath = 'media/opened/'.$snapchat->username."-".$sender."-".$mediaid.".mp4";
      }

      $data = null;

      if(!file_exists($filePath))
      {
        $data = $snapchat->getMedia($mediaid);

        if($data)
        {
          file_put_contents($filePath, $data);
        }
      }
      else
      {
        if(filesize($filePath) == 0)
        {
          $data = $snapchat->getMedia($mediaid);

          if($data)
          {
            file_put_contents($filePath, $data);
          }
        }
      }

      $extractFolder = "media/snaps/".$snapchat->username."-".$sender."-".$mediaid;

      if(file_exists($extractFolder))
      {
        $files = scandir($extractFolder);

        foreach ($files as $file)
        {
          if(strpos($file,'media') !== false)
          {
            $extension = ".mp4";

            if (strpos($file, $extension) !== FALSE)
            {

            }
            else
            {
              if(file_exists($extractFolder."/".$file))
              {
                rename($extractFolder."/".$file, $extractFolder."/".$file.$extension);
              }
            }

            if(strpos($file, $extension) !== false)
            {
              $snapfile = $extractFolder."/".$file;
            }
            else
            {
              $snapfile = $extractFolder."/".$file.$extension;
            }

            //$snap->snapmedia = HOST."tests/".$snapfile;

            $filePath = $snapfile;
          }
          else if(strpos($file,'overlay') !== false)
          {
            $extension = ".png";

            if (strpos($file, $extension) !== FALSE)
            {

            }
            else
            {
              if(file_exists($extractFolder."/".$file))
              {
                rename($extractFolder."/".$file, $extractFolder."/".$file.$extension);
              }
            }

            //$snap->snapoverlay = HOST."tests/".$extractFolder."/".$file.$extension;
          }
        }
      }

      if(file_exists($filePath))
      {
        $response->status = OKAY;
        $response->message = HOST."/tests/".$filePath;
      }
      else
      {
        $response->status = BAD;
        $response->message = "Sorry, I was unable to download the snap :( Please try again. ".$mediaid;
      }
    }
    else if($action == "getmediatest")
    {
      $data = $snapchat->getMedia($_GET['mediaid']);
      var_dump($data);
    }
    else if($action == "getfriendstories")
    {
      ini_set('max_execution_time', 300);
      
      $friendstories = $snapchat->getFriendStories();

      if($friendstories && count($friendstories) > 0)
      {
        foreach ($friendstories as $friendstory) 
        {
          $friendstory->timeago = "";

          if($friendstory->media_type == Snapchat::MEDIA_IMAGE)
          {
            $storyfile = "media/stories/".$snapchat->username."-".$friendstory->username."-".$friendstory->media_id.".jpg";
            $friendstory->storymediathumbnail = HOST."slir/w50/snapchat/tests/".$storyfile;
          }
          else if($friendstory->media_type == Snapchat::MEDIA_VIDEO || $friendstory->media_type == Snapchat::MEDIA_VIDEO_NOAUDIO)
          {
            $storyfile = "media/stories/".$snapchat->username."-".$friendstory->username."-".$friendstory->media_id.".mp4";
            $friendstory->storymediathumbnail = "http://i.imgur.com/JvQuvxP.png";
          }

          $friendstory->storymedia = HOST."tests/".$storyfile;

          if(!file_exists($storyfile))
          {
            $storydata = $snapchat->getStory($friendstory->media_id, $friendstory->media_key, $friendstory->media_iv);
            file_put_contents($storyfile, $storydata);
          }
          else
          {
            if(filesize($storyfile) == 0)
            {
              $storydata = $thesnapchat->getStory($thestory->story->media_id, $thestory->story->media_key, $thestory->story->media_iv);
              file_put_contents($storyfile, $storydata);
            }
          }
        }
      }

      $response = $friendstories;
    }
    else if($action == "markopened")
    {
      $mediaid  = $_GET['mediaid'];
      $snapchat->markSnapViewed($mediaid);
      $response->status = OKAY;
    }
    else if($action == "markscreenshotted")
    {
      $mediaid  = $_GET['mediaid'];
      $snapchat->markSnapShot($mediaid);
      $response->status = OKAY;
    }
    else if($action == "markstoryviewedscreenshotted")
    {
      $storyid  = $_GET['storyid'];
      $snapchat->markStoryViewed($storyid, 1);
      $response->status = OKAY;
    }
    else if($action == "upload")
    {
      $mediatype    = $_GET['mediatype'];

      $addtostory = "false";

      if(isset($_GET['addtostory']))
      {
        $addtostory = $_GET['addtostory'];
      }

      $recipients = $_GET['recipients'];

      if(strpos($recipients, 'mystory') !== false)
      {
        $addtostory = "true";

        if(strpos($recipients, 'mystory,') !== false)
        {
          $recipients = str_replace("mystory,", "", $recipients);
        }
        else
        {
          $recipients = str_replace("mystory", "", $recipients);
        }
      }

      if(isset($_GET['addfriend']))
      {
        if($_GET['addfriend'] == "true")
        {
          if($recipients != "" && strpos($recipients, ',') !== true) // if comma does not exist
          {
            $snapchat->addFriend($recipients);
          }
        } 
      }

      if ($_FILES["theFile"]["error"] > 0)
      {
        $response->status = BAD;
        $response->message = "Error: " .$_FILES["theFile"]["error"];
      }
      else
      {
        $blob = file_get_contents($_FILES["theFile"]["tmp_name"]);

        $id = "";

        if($mediatype == "image")
        {
          $timer      = $_GET['timer'];

          $id = $snapchat->upload(Snapchat::MEDIA_IMAGE, $blob);

          if($id != FALSE)
          {
            $snapchat->send($id, array($recipients), $timer);

            if($addtostory == "true")
            {
              $snapchat->setStory($id, Snapchat::MEDIA_IMAGE, $timer);
            }

            // if(strlen($recipients) > 20)
            // {
            //   $recipients = substr($recipients, 0, 10) . "-".strlen($recipients);
            // }

            // // save to server
            // $savePath = 'media/sent/'.$snapchat->username."-".$recipients."-".$id.".jpg";
            // file_put_contents($savePath, $blob);
          }
        }
        else if($mediatype == "video")
        {
          $timer      = $_GET['timer'];

          $id = $snapchat->upload(Snapchat::MEDIA_VIDEO, $blob);

          if($id != FALSE)
          {
            $snapchat->send($id, array($recipients), $timer);

            if($addtostory == "true")
            {
               $snapchat->setStory($id, Snapchat::MEDIA_VIDEO, $timer);
            }

            // $savePath = 'media/sent/'.$snapchat->username."-".$recipients."-".$id.".mp4";
            // file_put_contents($savePath, $blob);
          }
        }

        if($id != "")
        {
          $response->status = OKAY;
          $response->message = $id;
        }
        else
        {
          $response->status = BAD;
          $response->message = "Sorry, I was unable to send the snap :( Please try again. : ".$id;
        }
      }
    }
    else if($action == "getsnaps")
    {
      $snaps = $snapchat->getSnaps();

      $openedsnaps = array();
      $deliveredsnaps = array();

      if($sortby != "")
      {
        if(count($snaps) > 0)
        {
          foreach ($snaps as $snap) 
          {
            if($snap->status == Snapchat::STATUS_OPENED)
            {
              array_push($openedsnaps, $snap);
            }
            
            if($snap->status == Snapchat::STATUS_DELIVERED)
            {
              array_push($deliveredsnaps, $snap);
            }
          }
        }

        if($sortby == "opened")
        {
          $snaps = array_merge((array)$openedsnaps, (array)$deliveredsnaps);
        }
        else if($sortby == "delivered")
        {
          $snaps = array_merge((array)$deliveredsnaps, (array)$openedsnaps);
        }
      }
      
      // REMOVE REQUESTS
      
      $newsnaps = array();

      if(count($snaps) > 0)
      {
        foreach ($snaps as $snap) 
        {
          $snap->loaded     = false;
          $snap->timeleft   = $snap->time;
          $snap->videourl   = "";
          $snap->timeago    = "";
          $snap->imagedata  = "";

          if($snap->media_type < 3)
          {
            if($showonly == "photo")
            {
              if($snap->media_type == Snapchat::MEDIA_IMAGE)
              {
                array_push($newsnaps, $snap);
              }
            }
            else if($showonly == "video")
            {
              if($snap->media_type == Snapchat::MEDIA_VIDEO || $snap->media_type == Snapchat::MEDIA_VIDEO_NOAUDIO)
              {
                array_push($newsnaps, $snap);
              }
            }
            else
            {
              array_push($newsnaps, $snap);
            }
          }
        }

        if($_GET['feedsmaxtoshow'] != "unlimited")
        {
          $newsnaps = array_slice($newsnaps, 0, $_GET['feedsmaxtoshow']);
        }
      }
 
      // REMOVE REQUESTS

      $response = $newsnaps;
    }
    else if($action == "getaddedfriends")
    {
      $friends = $snapchat->getAddedFriends();

      if($friends != false)
      {
        $response = $friends;
      }
    }
    else if($action == "getfriends")
    {
      $friends = $snapchat->getFriends();

      if(isset($_GET['hideblockedanddeleted']))
      {
        if($_GET['hideblockedanddeleted'] == "true")
        {
          if(count($friends) > 0)
          {
            $tempFriendsArray = array();
          
            foreach ($friends as $friend)
            {
              if($friend->type == Snapchat::FRIEND_CONFIRMED || $friend->type == Snapchat::FRIEND_UNCONFIRMED)
              {
                array_push($tempFriendsArray, $friend);
              }
            }

            $friends = $tempFriendsArray;
          }
        }
      }

      $response = $friends;
    }
    else if($action == "getbestfriends")
    {
      $friendusername = $_GET['friendusername'];

      $bestfriends = $snapchat->getBests(array($friendusername));

      if(count($bestfriends) > 0 && $bestfriends != false)
      {
        $response->status       = OKAY;
        $response->score        = $bestfriends->{$friendusername}->{'score'};
        $response->bestfriends  = $bestfriends->{$friendusername}->{'best_friends'};
        $response->bestfriends  = $bestfriends->{$friendusername}->{'best_friends'};
      }
      else
      {
        $response->status = BAD;
        $response->message = $friendusername." has no best friends and scores yet. :(";
      }
    }
    else if($action == "clearfeeds")
    {
      $snapchat->clearFeed();
      $response->status = OKAY;
    }
    else if($action == "savesettings")
    {
      if(isset($_POST['email']))
      {
        $snapchat->updateEmail($_POST['email']);
      }

      if(isset($_POST['whocansendsnaps']))
      {
        $snapchat->updatePrivacy($_POST['whocansendsnaps']);
      }

      if(isset($_POST['displayname']))
      {
        $snapchat->setDisplayName($snapchat->username, $_POST['displayname']);
      }

      $response->status = OKAY;
    }
    else if($action == "setdisplayname")
    {
      if(isset($_POST['username']) && isset($_POST['displayname']))
      {
        if($snapchat->setDisplayName($_POST['username'], $_POST['displayname']))
        {
          $response->status = OKAY;
        }
      }
    }
    else if($action == "addfriend")
    {
      if(isset($_POST['addFriendUsername']))
      {
        if($snapchat->addFriend($_POST['addFriendUsername']))
        {
          $response->status = OKAY;
        }
      }
    }
    else if($action == "deletefriend")
    {
      if(isset($_POST['username']))
      {
        if($snapchat->deleteFriend($_POST['username']))
        {
          $response->status = OKAY;
        }
      }
    }
    else if($action == "block")
    {
      if(isset($_POST['username']))
      {
        if($snapchat->block($_POST['username']))
        {
          $response->status = OKAY;
        }
      }
    }
    else if($action == "unblock")
    {
      if(isset($_POST['username']))
      {
        if($snapchat->unblock($_POST['username']))
        {
          $response->status = OKAY;
        }
      }
    }
  }

  if($action == "login")
  {
    if(isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] != "" && $_POST['password'] != "")
    {
      $theusername = trim($_POST['username']);
      $thepassword = trim($_POST['password']);

      $snapchat = new Snapchat($theusername, $thepassword);

      $result = $snapchat->login($theusername, $thepassword);

      if($snapchat->username == FALSE || $snapchat->auth_token == FALSE)
      {
        $response->status = BAD;
        $response->message = (!empty($result->message) ? $result->message: "Sorry for the issues when logging in. If you used an email to login, please try your username instead.");
      }
      else
      {
        $result = $snapchat->getUpdates2();

        formatSnaps($result, $snapchat);

        $response->status = OKAY;
        $response->message = $result;
      }
    }
    else
    {
      $response->status = BAD;
      $response->message = "Please enter a username and a password.";
    }
  }
  else if($action == "loginnew")
  {
    if(isset($_GET['username']) && isset($_GET['password']) && $_GET['username'] != "" && $_GET['password'] != "")
    {
      $snapchat = new Snapchat($_GET['username'], $_GET['password']);

      $result = $snapchat->login($_GET['username'], $_GET['password']);

      if($snapchat->username == FALSE || $snapchat->auth_token == FALSE)
      {
        $response->status = BAD;
        $response->message = (!empty($result->message) ? $result->message: "Sorry for the issues when logging in. If you used an email to login, please try your username instead.");
      }
      else
      {
        $result = $snapchat->getUpdates2();

        formatSnaps($result, $snapchat);

        $response->status = OKAY;
        $response->message = $result;
      }
    }
    else
    {
      $response->status = BAD;
      $response->message = "Please enter a username and a password.";
    }
  }
  else if($action == "login2")
  {
    if(isset($_GET['username']) && isset($_GET['password']) && $_GET['username'] != "" && $_GET['password'] != "")
    {
      $snapchat = new Snapchat($_GET['username'], $_GET['password']);

      $result = $snapchat->login($_GET['username'], $_GET['password']);

      if($snapchat->username == FALSE || $snapchat->auth_token == FALSE)
      {
        $response->status = BAD;
        $response->message = $result;
      }
      else
      {
        $result = $snapchat->getUpdates2();

        formatSnaps($result, $snapchat);

        $response->status = OKAY;
        $response->message = $result;
      }
    }
    else
    {
      $response->status = BAD;
      $response->message = "Please enter a username and a password.";
    }
  }
  else if($action == "register")
  {
    if(
      isset($_GET['username']) && isset($_GET['password']) && isset($_GET['email']) && isset($_GET['birthday']) && 
      $_GET['username'] != "" && $_GET['password'] != "" && $_GET['email'] != "" && $_GET['birthday'] != "")
    {
      $snapchat   = new Snapchat();
      $result     = $snapchat->register($_GET['username'], $_GET['password'], $_GET['email'], $_GET['birthday']);

      if($snapchat->auth_token != "")
      {
        $response->status   = OKAY;
        $response->result   = $result;
        $response->message  = "Successfully Registered and Logged In.";
      }
      else
      {
        $response->status = BAD;
        $response->result = $result;
        $response->message = ($result->message != null ? $result->message : "Please try again.");

        if($response->message == "Please upgrade Snapchat to the latest version to create an account.")
        {
          $response->message = "Currently, snapchat disabled registration for all iPhone and Android SnapChat Applications with older versions and Snap2Chat is also affected. To temporarily register, please use a friend's iPhone or Android and use it to register an account. I am still trying to solve the problem and really sorry for the inconvenience. ";
        }
      }
    }
    else
    {
      $response->status = BAD;
      $response->message = "Please enter a username, password and an email.";
    }
  }
}
else
{
  $response->status = BAD;
  $response->message = "NO ACTION PROVIDED";
}

echo json_encode($response);

function formatSnaps($result, $thesnapchat)
{
  if($result)
  {
    if(is_object($result))
    {
      $result->updates_response->friendsCount = count($result->updates_response->friends);
    }

    if(count($result->updates_response->snaps) > 0)
    {
      $friendRequests = array();
      $newSnapsArray = array();

      $unopenedSnapsCount = 0;

       // SNAPS

      foreach ($result->updates_response->snaps as $snap) 
      {
        if($snap->m == SnapChat::MEDIA_FRIEND_REQUEST)
        {
          array_push($friendRequests, $snap);
          continue;
        }

        $snap->media_id         = empty($snap->c_id) ? FALSE : $snap->c_id;
        $snap->media_type       = $snap->m;
        $snap->time             = empty($snap->t) ? FALSE : $snap->t;
        $snap->sender           = empty($snap->sn) ? $result->updates_response->username : $snap->sn;
        $snap->recipient        = empty($snap->rp) ? $result->updates_response->username : $snap->rp;
        $snap->status           = $snap->st;
        $snap->screenshot_count = empty($snap->c) ? 0 : $snap->c;
        $snap->sent             = $snap->sts;
        $snap->opened           = $snap->ts;
        $snap->loaded           = false;
        $snap->loading          = false;
        $snap->send             = false;
        $snap->beingviewed      = false;
        $snap->timeleft         = $snap->time;
        $snap->videourl         = "";
        $snap->timeago          = ($snap->status == SnapChat::STATUS_OPENED ? timeSince($snap->opened / 1000) : timeSince($snap->sent / 1000));
        $snap->displayname      = "";
        $snap->load             = true;
        $snap->zipped           = empty($snap->zipped) ? false : $snap->zipped;

        // ZIPPED

        if($snap->zipped == true && ($snap->media_type == Snapchat::MEDIA_VIDEO || $snap->media_type == Snapchat::MEDIA_VIDEO_NOAUDIO) && $snap->time != false)
        {
          $snapBaseName   = $thesnapchat->username."-".$snap->sender."-".$snap->id;
          $snapfile       = "media/snaps/".$snapBaseName.".zip";

          if(!file_exists($snapfile))
          {
            $snapdata = $thesnapchat->getMedia($snap->id);
            file_put_contents($snapfile, $snapdata);
          }
          else
          {
            if(filesize($snapfile) == 0)
            {
              $snapdata = $thesnapchat->getMedia($snap->id);
              file_put_contents($snapfile, $snapdata);
            }
          }

          $extractFolder = "media/snaps/".$snapBaseName;

          if (!file_exists($extractFolder))
          {
              mkdir($extractFolder, 0777, true);
          }

          $zip = new ZipArchive;
          $res = $zip->open($snapfile);

          if ($res === TRUE) 
          {
            $zip->extractTo($extractFolder);
            $zip->close();
          }

          $files = scandir($extractFolder);

          foreach ($files as $file)
          {
            if(strpos($file,'media') !== false)
            {
              $extension = ".mp4";

              if (strpos($file, $extension) !== FALSE)
              {

              }
              else
              {
                if(file_exists($extractFolder."/".$file))
                {
                  rename($extractFolder."/".$file, $extractFolder."/".$file.$extension);
                }
              }

              if(strpos($file, $extension) !== false)
              {
                $snapfile = $extractFolder."/".$file;
              }
              else
              {
                $snapfile = $extractFolder."/".$file.$extension;
              }

              $snap->snapmedia = HOST."tests/".$snapfile;
            }
            else if(strpos($file,'overlay') !== false)
            {
              $extension = ".png";

              if (strpos($file, $extension) !== FALSE)
              {

              }
              else
              {
                if(file_exists($extractFolder."/".$file))
                {
                  rename($extractFolder."/".$file, $extractFolder."/".$file.$extension);
                }
              }

              $snap->snapoverlay = HOST."tests/".$extractFolder."/".$file.$extension;
            }
          }
        }

        // ZIPPED

        $theusername = "";

        if($thesnapchat->username == $snap->sender && $thesnapchat->username == $snap->recipient)
        {
            $theusername = $thesnapchat->username;
        }
        else if($thesnapchat->username == $snap->sender)
        {
            $theusername = $snap->recipient;
        }
        else if($thesnapchat->username == $snap->recipient)
        {
            $theusername = $snap->sender;
        }

        foreach ($result->updates_response->friends as $thefriend) 
        {
          if($thefriend->name == $theusername)
          {
            $snap->displayname = $thefriend->display;
            break;
          }
        }

        if($snap->status == SnapChat::STATUS_DELIVERED && $snap->recipient == $thesnapchat->username && $snap->time != false) // is 
        {
          $unopenedSnapsCount++;
        }

        if(isset($_GET['showonly']))
        {
          $showonly = $_GET['showonly'];

          if($showonly == "photo")
          {
            if($snap->media_type == Snapchat::MEDIA_IMAGE)
            {
              array_push($newSnapsArray, $snap);
            }
          }
          else if($showonly == "video")
          {
            if($snap->media_type == Snapchat::MEDIA_VIDEO || $snap->media_type == Snapchat::MEDIA_VIDEO_NOAUDIO)
            {
              array_push($newSnapsArray, $snap);
            }
          }
          else
          {
            array_push($newSnapsArray, $snap);
          }
        }
        else
        {
          array_push($newSnapsArray, $snap);
        }
      }

      $newFriendRequests = array();

      if(count($friendRequests) > 0)
      {
        foreach ($friendRequests as $friendRequest) 
        {
          if(!friendRequestExists($friendRequest,  $result->updates_response->friends))
          {
            array_push($newFriendRequests, $friendRequest);
          }
        }
      }

      $result->updates_response->unopened = $unopenedSnapsCount;

      if(isset($_GET['maxtoshow']))
      {
        $maxtoshow = $_GET['maxtoshow'];
      }

      if($maxtoshow != "unlimited")
      {
        $newSnapsArray = array_slice($newSnapsArray, 0, $maxtoshow);
      }

      $result->updates_response->snaps            = $newSnapsArray;
      $result->updates_response->friend_requests  = $newFriendRequests;
    }

    // FRIENDS

    if($result->updates_response->bests && count($result->updates_response->bests) > 0)
    {
      $newBestFriendsArray = array();

      $bestFriend                 = new BestFriend();
      $bestFriend->username       = "mystory";
      $bestFriend->name           = "mystory";
      $bestFriend->display        = "My Story";
      $bestFriend->type           = "mystory";

      array_push($newBestFriendsArray, $bestFriend);

      foreach ($result->updates_response->bests as $best) 
      {
        $bestFriend = new BestFriend();
        $bestFriend->username       = $best;
        $bestFriend->name           = $best;
        $bestFriend->display        = "";
        $bestFriend->type           = "bestfriend";

        array_push($newBestFriendsArray, $bestFriend);
      }

      $newRecentFriendsArray = array();

      foreach ($result->updates_response->recents as $recent) 
      {
        $recentFriend = new RecentFriend();
        $recentFriend->username       = $recent;
        $recentFriend->name           = $recent;
        $recentFriend->display        = "";
        $recentFriend->type           = "recentfriend";
        array_push($newRecentFriendsArray, $recentFriend);
      }

      $tempFriendsArray = array();

      foreach ($result->updates_response->friends as $friend) 
      {
        if(isset($_GET['hideblockedanddeleted']))
        {
          if($_GET['hideblockedanddeleted'] == "true")
          {
            if($friend->type == Snapchat::FRIEND_CONFIRMED || $friend->type == Snapchat::FRIEND_UNCONFIRMED)
            {
              array_push($tempFriendsArray, $friend);
            }
          }
        }
      }

      if(isset($_GET['hideblockedanddeleted']))
      {
        if($_GET['hideblockedanddeleted'] == "false")
        {
          $tempFriendsArray = $result->updates_response->friends;
        }
      }
      else
      {
        $tempFriendsArray = $result->updates_response->friends;
      }

      $result->updates_response->best_friends = $newBestFriendsArray;
      $result->updates_response->friends = array_merge((array)$newBestFriendsArray,(array)$newRecentFriendsArray, (array)$tempFriendsArray);
    }

    // FRIEND STORES

    //$result->stories_response->orig_stories   = $result->stories_response->friend_stories;

    $friend_stories = array();

    if(count($result->stories_response->friend_stories) > 0)
    {
      foreach ($result->stories_response->friend_stories as $user) 
      {
        if(count($user->stories) > 0)
        {
          foreach ($user->stories as $thestory) 
          {
            $thestory->story->timeago = timeSince($thestory->story->timestamp / 1000);

            array_push($friend_stories, $thestory->story);
          }
        }
      }
    }

    // MY STORES

    $my_stories = array();

    if(count($result->stories_response->my_stories) > 0)
    {
      foreach ($result->stories_response->my_stories as $thestory) 
      {
        $thestory->story->timeago = timeSince($thestory->story->timestamp / 1000);

        array_push($my_stories, $thestory->story);
      }
    }

    $whole_stories = array_merge((array)$my_stories,(array)$friend_stories);

    if(count($whole_stories) > 0)
    {
      foreach ($whole_stories as $story) 
      {
        $story->timeago = timeSince($story->timestamp / 1000);
        $story->loaded  = false;
        $story->loading = false;
        $story->load  = true;

        $storyBaseName  = $thesnapchat->username."-".$story->username."-".$story->media_id;
        $storyfile      = "media/stories/".$storyBaseName;
        $storyfileCopy  = $storyfile;
        $extension      = "";

        if($story->media_type == Snapchat::MEDIA_IMAGE)
        {
          $extension = ".jpg";
          $story->storymediathumbnail = HOST."slir/w50/snapchat/tests/".$storyfile;
        }
        else if($story->media_type == Snapchat::MEDIA_VIDEO || $story->media_type == Snapchat::MEDIA_VIDEO_NOAUDIO)
        {
          $story->storymediathumbnail = "http://imgur.com/YFCeC5M.jpg";

          if($story->zipped == true)
          {
             $extension = ".zip";
          }
          else
          {
             $extension = ".mp4";
          }
        }

        $storyfile = $storyfile.$extension;

        if(!file_exists($storyfile))
        {
          $storydata = $thesnapchat->getStory($story->media_id, $story->media_key, $story->media_iv);
          file_put_contents($storyfile, $storydata);
        }
        else
        {
          if(filesize($storyfile) == 0)
          {
            $storydata = $thesnapchat->getStory($story->media_id, $story->media_key, $story->media_iv);
            file_put_contents($storyfile, $storydata);
          }
        }

        if($story->zipped == true)
        {
          $extractFolder = "media/stories/".$storyBaseName;

          if (!file_exists($extractFolder)) 
          {
              mkdir($extractFolder, 0777, true);
          }

          $zip = new ZipArchive;
          $res = $zip->open($storyfile);

          if ($res === TRUE) 
          {
            $zip->extractTo($extractFolder);
            $zip->close();
          }

          $files = scandir($extractFolder);

          foreach ($files as $file)
          {
            if(strpos($file,'media') !== false)
            {
              $extension = ".mp4";

              if (strpos($file, $extension) !== FALSE)
              {

              }
              else
              {
                if(file_exists($extractFolder."/".$file))
                {
                  rename($extractFolder."/".$file, $extractFolder."/".$file.$extension);
                }
              }

              if(strpos($file, $extension) !== false)
              {
                $storyfile = $extractFolder."/".$file;
              }
              else
              {
                $storyfile = $extractFolder."/".$file.$extension;
              }
            }
            else if(strpos($file,'overlay') !== false)
            {
              $extension = ".png";

              if (strpos($file, $extension) !== FALSE)
              {

              }
              else
              {
                if(file_exists($extractFolder."/".$file))
                {
                  rename($extractFolder."/".$file, $extractFolder."/".$file.$extension);
                }
              }

              $story->storymediaPNG = HOST."tests/".$extractFolder."/".$file.$extension;
            }
          }
        }

        $story->storymedia = HOST."tests/".$storyfile;
      }
    }

    $result->stories_response->friend_stories = $whole_stories;
    $result->stories_response->my_stories     = $my_stories;
  }
}

function timeSince ($time)
{
    $time = time() - $time; // to get the time since that moment

    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) 
    {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s ago':' ago');
    }
}

function friendRequestExists($friendRequest, $friends)
{
  $exists = false;

  if(count($friends) > 0)
  {
    foreach ($friends as $myfriend) 
    {
      if($friendRequest->sn == $myfriend->name) // EXISTS
      {
        $exists = true;
        break;
      }
    }
  }

  return $exists;
}

class Response
{
  public $message;
  public $status = BAD;
}

class BestFriend
{
  public $username;
  public $name           = "";
  public $isBestFriend   = false;
  public $display        = "";
  public $type           = "bestfriend";
}

class RecentFriend
{
  public $username;
  public $name           = "";
  public $isRecentFriend = false;
  public $display        = "";
  public $type           = "recentfriend";
}


?>