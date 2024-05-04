<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Page</title>

  <link rel="stylesheet" href="./../../node_modules/datatables.net-dt/css/dataTables.dataTables.min.css">
  <link rel="stylesheet" href="./../../node_modules/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="./../../node_modules/sweetalert2/dist/sweetalert2.min.css">

  <script src="./../../node_modules/jquery/dist/jquery.min.js"></script>
  <script src="./../../node_modules/datatables.net/js/dataTables.min.js"></script>
  <script src="./../../node_modules/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
  <script src="./../../node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <script src="./../../node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>

  <script defer="true" src="./../js/home_page.js"></script>
</head>
<body>
  <!-- User Display Table 
    Allow Add User
    Display All Users
    Allow Clickable Entry User
  -->
    <button 
      type="button" 
      class="btn btn-success m-2 p-3" 
      data-bs-toggle="modal" 
      data-bs-target="#add-user-modal">
      ADD USER +
    </button>
    <div class="container-fluid">
    <div class="card mt-3">
      <div class="card-header">
        <h5 id="display-table-name">Users </h5>
      </div>
      <div class="card-body container-fluid">
        <table id="user-display" class="table table-bordered table-dark table-striped-columns table-striped">
          <h1>Users</h1>
          <thead id="display-table-head">
            <tr>
              <th>Id</th>
              <th>Name</th>
              <th>Picture</th>
            </tr>
          </thead>
          <tbody id="display-table-body">

          <?php
            include_once('./../php/dbOperations.php');
            $sql = "SELECT * FROM user;";
            $result = DataBaseOperations::execute_and_return_error_msg($sql);

            $contents = mysqli_fetch_array($result["result"]);

            while(  $contents != null){
              echo "<tr class=\"user-container\" id=\"".$contents['id']."\" >";
              echo "<td class=\"user-data user-id-container\">" . $contents['id']."</td>";
              echo "<td class=\"user-data user-name-container\">" . $contents['name']."</td>";
              echo "<td class=\"d-flex justify-content-center\"><img src=\"./../uploads/" . $contents['picture']."\" class=\"user-data user-picture-container rounded-circle img-fluid max-height-100 max-width-200\"></td></tr>";

              $contents = mysqli_fetch_array($result["result"]); 
            }
          ?>

          </tbody>
        </table
      </div>
    </div>  
  </div>

  <!-- Insert Modal 
    Triggered by add user button 
  -->
  <div class="modal" id="add-user-modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title">Add User</h1>
        </div>
        <div class="modal-body">
          <p>
            Add User Entry
          </p>
          <form action="#" method="post" enctype="multipart/form-data" id="addUserForm">

            <label for="add-user-name">name</label> <br>
            <input type="text" name="add-user-name" id="input-add-user-name" class="user-name-container"> <br>
            
            <label for="add-user-picture">profile picture</label> <br>
            <input type="file" name="add-user-picture" id="input-add-user-profile-picture"> <br>

            <label>Preview</label> <br>
            <img 
              src="./../assets/No Image Selected.PNG" 
              alt="Preview of Selected image, if there is no image selected, the img presented is blank"
              id="add-user-profile-picture-preview"
              class="img-thumbnail"  
            >
            <br>

            <button type="button" id="add-new-user">Submit</button> <br>
          </form>
        </div>
        <div class="modal-footer">
          <button 
            type="button" 
            class="btn btn-danger" 
            data-bs-dismiss="modal"
            data-bs-target="#add-user-modal" 
            data-bs-toggle="modal">
            Close
          </button>
        </div>
      </div>

    </div>
  </div>

  <!-- Update Modal
    Triggered by clicking an entry into the table
  -->
    <div class="modal" id="edit-user-modal">
    <div class="modal-dialog">
      <div class="modal-content">
        
        <div class="modal-header">
          <h1 class="modal-title">Edit User Profile<br>
        </div>
        
        <div class="modal-body">
          <form action="#" enctype="multipart/form-data" id="edit-user-form">

            <input type="hidden" name="update_column" value="id">
            <input type="hidden" name="update_column_value" id="update-column-value-container" value="">


            <label for="edit-user-id">id</label> <br>
            <h6 id="edit-user-id" name="edit-user-id"></h6> <br>

            <label for="edit-user-name">name</label> <br>
            <input class="modal-title user-name-container" id="edit-user-name" name="edit-user-name" > <br>

            <label for="edit-user-profile-picture">current profile picture</label> <br>
            <img 
              src="" alt="User Profile Picture" 
              class="img-thumbnail" 
              name="edit-user-profile-picture" 
              id="edit-user-profile-picture-preview"
            >

            <label for="new-edit-user-profile-picture">Profile Picture</label> <br>
            <input type="file" name="new-edit-user-profile-picture" id="edit-profile-picture"> <br>

            <label>Preview</label> <br>
            <img 
              src="./../assets/No Image Selected.PNG" 
              alt="Preview of Selected image, if there is no image selected, the img presented is blank"
              id="edit-profile-picture-preview"
              class="img-thumbnail"  
            >
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" id="edit-user-trigger" class="btn btn-success" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#edit-user-modal">submit changes</button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#edit-user-modal">Close</button>
        </div>
      </div>

    </div>
  </div>
</body>
</html>