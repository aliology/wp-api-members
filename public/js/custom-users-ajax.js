jQuery(document).ready(function($) {
    $(document).on('click', '.user-detail-link', function(e) {
        e.preventDefault();
        var userId = $(this).data('userid');
        fetchUserDetails(userId, 3);
    });

    function fetchUserDetails(userId, retries) {
        $.ajax({
            url: customUsersAjax.ajaxurl,
            type: 'POST',
            data: {
                'action': 'fetch_user_details',
                'user_id': userId,
                'nonce': customUsersAjax.nonce
            },
            timeout: 20000,
            success: function(response) {
                displayFormattedUserDetails(response);
            },
            error: function(response) {
                if (retries > 0) {
                    console.log('Retrying... attempts remaining: ' + retries);
                    fetchUserDetails(userId, retries - 1);
                } else {
                    var errorMessage = response.responseJSON && response.responseJSON.error ? response.responseJSON.error : 'Failed to load details.';
                    $('#user-details-display').html('<p class="error-message">' + errorMessage + '</p>');
                }
            }
        });
    }

    function displayFormattedUserDetails(response) {
        var userDetails = JSON.parse(response);
        var formattedDetails = '<h3>User Details</h3>' +
                               '<div class="user-details">' +
                               '<p><label>ID: </label>' + userDetails.id + '</p>' +
                               '<p><label>Name: </label>' + userDetails.name + '</p>' +
                               '<p><label>Username: </label>' + userDetails.username + '</p>' +
                               '<p><label>E-mail: </label><a href="mailto:' + userDetails.email + '">' + userDetails.email + '</a></p>' +
                               '<p><label>Phone: </label>' + userDetails.phone + '</p>' +
                               '<p><label>Address:</label><br>' +
                               '   <label> Street: </label>' + userDetails.address.street + '<br>' +
                               '   <label> Suite: </label>' + userDetails.address.suite + '<br>' +
                               '   <label> City: </label>' + userDetails.address.city + '<br>' +
                               '   <label> Zip Code: </label>' + userDetails.address.zipcode + '<br>' +
                               '   <label> GEO: lat: </label>' + userDetails.address.geo.lat + '<label> lng: </label>' + userDetails.address.geo.lng + '</p>' +
                               '<p><label>Website: <a href="https://</label>' + userDetails.website + '" target="_blank">' + userDetails.website + '</a></p>' +
                               '<p><label>Company Name: </label>' + userDetails.company.name + '</p>' +
                               '<p><label>Company Field: </label>' + userDetails.company.catchPhrase + '</p>' +
                               '<p><label>Company Additional: </label>' + userDetails.company.bs + '</p>' +
                               '</div>';
        $('#user-details-display').html(formattedDetails);
        $('#user-details-display')[0].scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
});
