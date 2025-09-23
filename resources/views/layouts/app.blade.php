<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Event Management')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        @yield('content')
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <!-- Script to get csrf token -->
	<script type='text/javascript'> function getToken(){ return "{{csrf_token()}}"; } </script>
    <!-- Jquery cdn link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>

    <script>
// starter JavaScript for disabling form submissions if there are invalid fields
(function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
})();
window.onload = () => {
	reloadEvents();
}
function showAlert(message, type = 'success') {
    const alertDiv = document.getElementById('alert_showing_div');

    // Reset the alert content
    alertDiv.innerHTML = ''; // remove old content

    // Create message span
    const msgSpan = document.createElement('span');
    msgSpan.classList.add('alert-message');
    msgSpan.innerText = message;

    // Add a close button
    const closeBtn = document.createElement('button');
    closeBtn.type = 'button';
    closeBtn.classList.add('btn-close');
    closeBtn.onclick = () => { alertDiv.style.display = 'none'; };

    // Append elements
    alertDiv.appendChild(msgSpan);
    alertDiv.appendChild(closeBtn);

    // Add alert class based on type
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;

    // Show the alert
    alertDiv.style.display = 'block';
}
function reloadEvents() {
    return fetch("/eventsDetails", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": getToken()
        }
    })
    .then(res => res.json())
   .then(data => {
        // Extract the list from the "data" key
        const eventsList = data.data.map(item => ({
            id: item.id,
            title: item.title,
            date: item.date,
            location: item.location,
            seats: item.seats
        }));
     propergateEventsTable(eventsList);
    })
    .catch(err => console.error("Error:", err));
}
function propergateEventsTable( eventsList )
{
		if( typeof(eventsList) === "undefined" ) { return false; }
		if( typeof(eventsList) === "string" )
		{
			try { eventsList = JSON.parse(eventsList); }
			catch (e) { return false; }
		}
		if( !Array.isArray(eventsList) ) { var temp = eventsList; eventsList = new Array(); eventsList.push(temp); }
		var table = document.getElementById( "events_list" );
		table.innerHTML = "";
		for(var i = 0, item; item = eventsList[i]; i++)
		{
			var row = table.insertRow(), cell, element;
			// Event ID
			cell = row.insertCell();
			cell.innerHTML = ( typeof(item.id) === "undefined" ? "-" : item.id );
			cell.style.display = "none";
            cell = row.insertCell();
			// Events Number
			cell.innerHTML = i+1;
			cell = row.insertCell();
			// Events Title
			cell.innerHTML = ( typeof(item.title) === "undefined" ? "-" : item.title );
			cell = row.insertCell();
            // Events Date
			cell.innerHTML = ( typeof(item.date) === "undefined" ? "-" : item.date );
			cell = row.insertCell();
            // Events Location
			cell.innerHTML = ( typeof(item.location) === "undefined" ? "-" : item.location );
			cell = row.insertCell();
			// Events Seats
			cell.innerHTML =  ( typeof(item.seats) === "undefined" ? "0" : item.seats );
			// Events Details Link
			cell = row.insertCell();
            cell.style.textAlign="center";
			element = document.createElement( "button" );
			element.classList.add( "btn" );
			element.classList.add( "btn-small" );
			element.classList.add( "btn-outline-primary" );
			element.classList.add( "me-2" );
			element.innerHTML = "Edit";
            element.onclick = function() {
                let eventid=this.parentElement.parentElement.cells[0].innerHTML;
                window.location.href = `/events/${eventid}/edit`;
            }
			cell.appendChild( element );
			// Delete Button
			element = document.createElement( "button" );
			element.classList.add( "btn" );
			element.classList.add( "btn-small" );
			element.classList.add( "btn-outline-danger" );
			element.appendChild( document.createTextNode("Delete") );
			element.onclick = function() {
				let eventid=this.parentElement.parentElement.cells[0].innerHTML;
				deleteEvent(eventid);
			}
			cell.appendChild( element );

		}
}
// function to delete event
 function deleteEvent(id) {
    if (!confirm('Are you sure you want to delete this event?')) return;

    fetch(`/events/${id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getToken()
        }
    })
    .then(res => res.json())
    .then(data => {
  if (data.status === 'success') {

    showAlert("Event Deleted!", "success");
    reloadEvents();
}
 else {

        }
    })
    .catch(err => {
        console.error(err);
    });
}
function saveEvent(form = null) {
    event.preventDefault();
    if (form == null) {
        form = document.getElementById("form_saveEvent");
    }
    if (form == null) {
        return null;
    }

    var data = {};



    // Get the fields value
    const title=document.querySelector("[name=title]").value;
    const date=document.querySelector("[name=date]").value;
    const location=document.querySelector("[name=location]").value;
    const seats=document.querySelector("[name=seats]").value;

     // Validation
    if (!title) {
        alert("Title is required");
        form.elements['title'].focus();
        return false;
    }

    if (!date) {
        alert("Date is required");
        form.elements['date'].focus();
        return false;
    }

    // Check for past date
    const selectedDate = new Date(date);
    const today = new Date();
    if (selectedDate < today) {
        alert("Date cannot be in the past");
        form.elements['date'].focus();
        return false;
    }

    if (!location) {
        alert("Location is required");
        form.elements['location'].focus();
        return false;
    }

    if (!seats) {
        alert("Seats is required");
        form.elements['seats'].focus();
        return false;
    }

    if (isNaN(seats) || seats < 0) {
        alert("Seats must be a number greater than or equal to 0");
        form.elements['seats'].focus();
        return false;
    }
    // Set values
    data.title = title;
    data.date = date;
    data.location = location;
    data.seats = seats;
    ajax_makeNewEvent(data);

    return data;
}
function ajax_makeNewEvent( requestData )
{
	// Create Request
	var data = {};
	if( typeof(requestData.title) !== "undefined") { data.title = requestData.title; }
	if( typeof(requestData.date) !== "undefined" ) { data.date = requestData.date; }
	if( typeof(requestData.location) !== "undefined" ) { data.location = requestData.location; }
	if( typeof(requestData.seats) !== "undefined" ) { data.location = requestData.seats; }
	var formData = new FormData();
	formData.append('title', requestData.title);
	formData.append('date', requestData.date);
	formData.append('location', requestData.location);
	formData.append('seats', requestData.seats);
	formData.append('_token',getToken());
      $.ajax({
        url: "{{ route('events.store') }}", // Correct route
        method: "POST",
        processData: false,
        contentType: false,
        data: formData,
        success: function(response) {
          window.location.href = "/events";

        },
        error: function(xhr) {
            if (xhr.status == 422) {
                // Validation errors
                let errors = xhr.responseJSON.errors;
                let firstError = Object.keys(errors)[0];
                alert(errors[firstError][0]);
            } else {
                alert("Something went wrong. Please try again.");
            }
        }
    });
}
    </script>
</body>
</html>
