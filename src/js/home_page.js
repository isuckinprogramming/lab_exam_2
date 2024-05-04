
function showAlert(icon, title, content){
// showAlert('error', 'Error', response.error_message_display);
Swal.fire({
  icon: icon,
  title: title,
  text: content,
  confirmButtonText: 'CONTINUE',
  allowEscapeKey: false,
  allowOutsideClick: false,
});
}

function respondToRequestForDatabaseInput(response, successMessage, modalId,selectorOfPreviewImage) { 
        
  // const status = response.status;
  // const error = response.error_essage;

  if (response.status) {

   
    // console.log("Ajax request is working")
    updateTableRow(
      response.recent_changed_entry_id, 
      $(selectorOfPreviewImage).attr("src"),
      $("#" + modalId + " form input.user-name-container").val()
    )

    $(selectorOfPreviewImage).attr("src", "");
    $("#" + modalId + " form input.user-name-container").val("")

    $(`#${modalId}`).modal("hide");
    showAlert('success', 'Success', successMessage) 

  } else {
    showAlert('error', 'Error', response.error_message_display);

    $(`#${modalId}`).modal("hide");
  }    
}

function updateTableRow(targetId, imageSrc, name) { 

  // targetId = targetId.trim();

  const rowWithNewData = $(`#${targetId}`);

  if (rowWithNewData.length === 0) { 
    const tableRowString = ` <tr class="user-container" id="${targetId}"> 
      <td class="user-data user-id-container">${targetId}</td>
      <td class="user-data user-name-container">${name}</td>
      <td>
        <img
          src="${imageSrc}" 
          class="user-data user-picture-container justify-content-center d-flex rounded-circle img-fluid max-height-100 max-width-200">
      </td>
    </tr>`;
    
    $htmlContent = $("#user-display tbody").html();
    
    $("#user-display tbody").html($htmlContent + tableRowString);
    
  //   $(`#${targetId}`).on(
  // 'click',
  // function (e) { 
  //   addUserToDatabase(e,"input-add-user-name");
  // }
  //   );

    $(`#${targetId}`).on(
      "click",
      displayContentsOfUserToEditModal
    )
    return;
  }

  const nameContainer = $("#" + targetId + " td.user-data.user-name-container"); 
  nameContainer.text(name);

  const imageContainer = $("#" + targetId + " td img.user-data.user-picture-container"); 
  imageContainer.attr("src", imageSrc);
  
  // $("#user-display").draw();
  // $("#user-display").draw();
}


function prepareFormData(formId, imageInputId, idOfPreviewImage) {

  const form = document.getElementById(formId);
  const imageInput = document.getElementById(imageInputId);

  const formData = new FormData(form);
  

  formData.append("picture", imageInput.files[0]);

  // $(`#${modalId} idOfPreviewImage`)
  
  if (imageInput.files.length <= 0) { 
    showAlert("error", "Please Fill All Fields", "Missing fields");
    return false;
  }

  return formData;
}

function handleErrorFromDatabaseRequest(errorObject, modalId) { 

  $(`#${modalId}`).hide();

  showAlert('error', 'Something Went Wrong Please Try Again', "Error Message inside console.");

  console.info(errorObject);
}

function requestToDatabase(
  operation, 
  phpActionUrl,
  formId,
  inputId,
  pfpInputId,
  modalId,
  successMessageToUser
) { 
  
  // const dataFromInputs = retrieveKeyValueDataFromInputs("container-for-input-label", "table-entry-input")
  // e.preventDefault();

  const formData = prepareFormData(formId, pfpInputId);

  if ($("#" + inputId).val() == "") { 
    showAlert("error", "Please Fill All Fields", "Missing fields");
    return;
  }

  if (!formData) {
    return;
  } else { 
    $(`#${pfpInputId}`).val("");

    $.ajax({
      method: "POST",
      url: phpActionUrl,
      data: formData,
      // contentType: "multipart/form-data",
      contentType: false,
      processData: false,
      dataType: 'JSON',
      success: function (response) {
        respondToRequestForDatabaseInput(
          response,
          successMessageToUser,
          modalId,
          "#edit-profile-picture-preview"
        )
      },
      error: function (errorObject) {
        handleErrorFromDatabaseRequest(errorObject, modalId);
      } 
    });
  }
}


function addUserToDatabase(e, inputId) { 
  
  e.preventDefault();

  
  const formData = prepareFormData("addUserForm", "input-add-user-profile-picture");
  
  
  if ($("#" + inputId).val() == "") { 
    showAlert("error", "Please Fill All Fields", "Missing fields");
    return;
  }

  if (!formData) { 
    return; 
  }

  $.ajax({
    method: "POST",
    url: "./../php/add_user.php",
    data: formData,
    // contentType: "multipart/form-data",
    contentType: false,
    processData: false,
    dataType: 'JSON',
    success: function (response) {
      respondToRequestForDatabaseInput(
        response,
        'Entry added!',
        "add-user-modal",
        "#add-user-profile-picture-preview"
      )
    },
    error: function (errorObject) {
      handleErrorFromDatabaseRequest(errorObject, "add-user-modal");
    } 

  });
}

function displayContentsOfUserToEditModal() { 
  
  const userDataContainers = $(this).find(".user-data");

  console.info(userDataContainers);

  const userImage = userDataContainers.filter('.user-picture-container');
  const imgSource = userImage.attr('src');
  $('#edit-user-profile-picture-preview').attr('src',imgSource); 
  
  const userId = userDataContainers.filter('.user-id-container');
  $("#edit-user-id").text(userId.text());

  const userName = userDataContainers.filter('.user-name-container');
  $("#edit-user-name").attr("placeholder",userName.text());

  $("#update-column-value-container").val(userId.text());

  $("#edit-user-modal").show();
}

function displayPreviewImage(inputFileEvent,idOfPreviewImageTag) { 

  console.info(inputFileEvent.target);

  const file = inputFileEvent.target.files[0];

  let previewedImageSrc = "";
  const fileReader = new FileReader();
  fileReader.onload = (fileReaderEvent) => { 

    previewedImageSrc = fileReaderEvent.target.result;
    $(`#${idOfPreviewImageTag}`).attr('src',previewedImageSrc );
    // fileReaderEvent.target.result;
  }

  fileReader.readAsDataURL(file);
  return previewedImageSrc;
}
let currentImageInPreview = "";
// Preview images
$("#edit-profile-picture").on(
  "change",
  function (inputFileEvent) { 
    currentImageInPreview = displayPreviewImage(inputFileEvent, "edit-profile-picture-preview");
  }
);
$("#input-add-user-profile-picture").on(
  "change",
  function (inputFileEvent) { 
   currentImageInPreview = displayPreviewImage(inputFileEvent, "add-user-profile-picture-preview");
  }
);

$("table tbody tr.user-container").on(
  "click",
  displayContentsOfUserToEditModal
);

$("#add-new-user").on(
  'click',
  function (e) { 
    addUserToDatabase(e,"input-add-user-name");
  }
);

$("#edit-user-trigger").on(
  "click", 
  function (e) {
    e.preventDefault
    requestToDatabase(
      "update_user_entry",
      "./../php/update_entry.php",
      "edit-user-form",
      "edit-user-name",
      "edit-profile-picture",
      "edit-user-modal",
      "UPDATE USER SUCCESS"
    );  
  }
)

// $("#user-display").DataTable();
