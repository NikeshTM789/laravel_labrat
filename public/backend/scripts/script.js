const delete_form_el = document.getElementById('delete-form');

function loadAfterDtTblLoaded() {
    deleteEventListener();
}

function deleteEventListener() {
    document.querySelectorAll('.dt-delete').forEach(function(el) {
        el.addEventListener('click', function(e) {
            e.preventDefault();

            Swal.fire({
              title: 'Are you sure?',
              // text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.isConfirmed) {
                const url = e.target.dataset.url;
                delete_form_el.setAttribute('action', url);
                delete_form_el.submit();
              }
            });

        });
    });
}

document.addEventListener("DOMContentLoaded", function() {
    // Code to be executed when the DOM is ready
    console.log("DOM is ready");
});

window.addEventListener('load', function() {
  console.log('Page loaded');
  // Your code to execute after the page has finished loading
});

/*----------  AJAX FETCH  ----------*/

function sendPostAjaxRequest(formBtn, url = window.location.href.split('#')[0]) {
    // Get the CSRF token value from the meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // Prepare the request headers
    const headers = {
        //'Content-Type': 'application/json',
        // 'Accept': 'application/json', // ($request->expectsJson())
        'X-Requested-With':'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken
    };
    // Prepare the request body
    let formEl = formBtn.closest('form');
    let body = new FormData(formEl);
    console.log(body.forEach(file => console.log("File: ", file)));
    // let body = JSON.stringify(data);
    // Send the AJAX request using Fetch API
    ajaxSubmitBtnChanger(formBtn)
    fetch(url, {
        method: 'POST',
        headers: headers,
        body: body
    })
    .then(response => response.json())
    .then(function(data) {
        console.log(data);
        if (data.hasOwnProperty('errors') || data.hasOwnProperty('exception')) {
            Toast.fire({
                icon: 'error',
                title: data.message
            });
        }else{
            Toast.fire({
                icon: 'success',
                title: data.message
            });
            formEl.reset();
            datatable.ajax.reload(); // reload datatable
        }
        ajaxSubmitBtnChanger(formBtn);
    }).catch(function(error) {
        console.error('Error:', error);
        ajaxSubmitBtnChanger(formBtn);
        Toast.fire({
            icon: 'error',
            title: data.message
        });
    });
}
let prevBtnValue = isBtnDisabled = false;

function ajaxSubmitBtnChanger(btn) {
    isBtnDisabled = !isBtnDisabled;
    btn.disabled = isBtnDisabled;
    if (btn.disabled) {
        prevBtnValue = btn.innerHTML;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
    } else {
        btn.innerHTML = prevBtnValue;
    }
}

