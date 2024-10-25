

    // Handle click event on search button
    $(".search_button").click(function () {
        // Redirect to search-travel.php with the search query
        window.location.href = "search-travel.php?query=" + $("#search").val();
    });

    // Handle click event on Add to Wishlist button
    $(document).on("click", ".wishlist-btn", function (event) {
        event.preventDefault(); // Prevent the default form submission

        // Get the form data
        var formData = $(this).closest("form").serialize();

        // Make an AJAX request to handle adding items to the wishlist
        $.ajax({
            url: "search-travel.php",
            type: "POST",
            data: formData,
            success: function (data) {
                // Handle the success response, if needed
                console.log(data);
            }
        });
    });




