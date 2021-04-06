$(document).ready(function () {
    getDataFromApi();

    // Init Data Table Cart
    if ($("#cart-table").length) {
        cartInit();
    }
    // Go to Sinle View
    if ($(".product-main-container").length) {
        $(".product-main-container").click(function () {
            goToSingleView(this);
        });
    }

    /**
     *  Cart Init Data Table
     */
    function cartInit() {
        $("#cart-table").DataTable();
    }

    /**
     * Asynchronous Request Get Data
     */
    function getDataFromApi() {
        let responseData = "";
        // Set Url
        var strUrl = "https://survey-api.npit.at/api/User/Me";
        // Init XMLHttpRequest
        var objRequest = new XMLHttpRequest();
        // Open Request
        objRequest.open("POST", strUrl);
        // Set Request Header Data Type
        objRequest.setRequestHeader("Accept", "application/json");
        // Set Request Type Header
        objRequest.setRequestHeader(
            "Authorization",
            "Bearer 9da12e9d-76a2-4c55-a4ae-ce97fcfda8c1"
        );
        // Set Content Type
        objRequest.setRequestHeader("Content-Type", "");
        // Request On Ready State Change
        objRequest.onreadystatechange = function () {
            if (objRequest.readyState === 4) {
                if (objRequest.status == 200) {
                    responseData = objRequest.responseText;
                    if (responseData.length > 0) {
                        responseData = JSON.parse(responseData);
                        setLocalStorage(responseData.data);
                        showUserHeader(responseData);
                        showAccountInfo(responseData.data);
                    }
                }
            }
        };
        objRequest.send();
    }

    /**
     * Function to save data in the local storage
     * @param {*} responseData
     */
    function setLocalStorage(responseData) {
        localStorage.setItem("email", responseData.email);
        localStorage.setItem("points", responseData.pointBalance);
        localStorage.setItem("first-name", responseData.firstName);
        localStorage.setItem("last-name", responseData.lastName);
    }
    /**
     * Show User Account Information
     */
    function showAccountInfo(responseData) {
        $(".spinner-container").hide();
        if ($("#account_first_name").length > 0) {
            $("#account_first_name").html(responseData.firstName);
        }
        if ($("#account_last_name").length > 0) {
            $("#account_last_name").html(responseData.lastName);
        }
        if ($("#account_response_id").length > 0) {
            $("#account_response_id").html(responseData.id);
        }
        if ($("#account_password").length > 0) {
            $("#account_password").html(responseData.password);
        }
        if ($("#account_email").length > 0) {
            $("#account_email").html(responseData.email);
        }
        if ($("#account_phone_number").length > 0) {
            $("#account_phone_number").html(responseData.pointBalance);
        }
        if ($("#account_user_points").length > 0) {
            $("#account_user_points").html(responseData.pointBalance);
        }
        if ($("#account_country").length) {
            $("#account_country").html(responseData.country);
        }
        if ($("#account_state").length) {
            $("#account_state").html(responseData.state);
        }
        if ($("#account_city").length) {
            $("#account_city").html(responseData.city);
        }
        if ($("#account_town").length) {
            $("#account_town").html(responseData.town);
        }
        if ($("#account_pinCode").length) {
            $("#account_pinCode").html(responseData.pinCode);
        }
        if ($("#account_address1").length) {
            $("#account_address1").html(responseData.address1);
        }
        if ($("#account_address2").length) {
            $("#account_address2").html(responseData.address2);
        }
        if ($(".account-info-container-inner").length > 0) {
            $(".account-info-container-inner").show();
            $(".account-info-container").css("display", "flex");
        }
    }

    /**
     * Show User Header Information
     */
    function showUserHeader(responseData) {
        $(".user-meta-spinner").hide();
        $(".user-meta-name").html(
            responseData.data.firstName + " " + responseData.data.lastName
        );
        $(".user-meta-points").html(
            "<strong>Points: </strong> " + responseData.data.pointBalance
        );
    }

    /**
     * Go to Single View
     * @param {*} objThis
     */
    function goToSingleView(objThis) {
        var permalink = $(objThis).data("permalink");
        if (permalink.length > 0) {
            window.location.href = permalink;
        }
    }
});

var objTheme = {
    elementLinks: "bottom-links",
    elementMenuFaIcon: "menu-fa-icon",
    classOpenMenu: "open-menu",
    classRotate: "rotate",

    MobileMenuOpenClose: function () {
        var elementLinks = document.getElementById(this.elementLinks);
        var elementMenuFaIcon = document.getElementById(this.elementMenuFaIcon);
        if (!elementLinks.classList.contains(this.classOpenMenu)) {
            elementLinks.classList.add(this.classOpenMenu);
            elementMenuFaIcon.classList.add(this.classRotate);
        } else {
            elementLinks.classList.remove(this.classOpenMenu);
            elementMenuFaIcon.classList.remove(this.classRotate);
        }
    },
    openTab: function (evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(
                " active",
                ""
            );
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    },
};

var defaultOpen = document.getElementById("defaultOpen");
if (defaultOpen) {
    defaultOpen.click();
}

$(document).ready(function () {
    var objCart = {
        buttonAddToCart: $(".add-to-cart"),
        buttonCheckout: $(".checkout-and-reedem"),
        removeItemButton: $(".remove-item-from-cart"),
        inputQtyChange: $(".cart-qty-change"),

        /**
         * Init Function
         */
        initCart: function () {
            this.buttonAddToCart.click(function () {
                objCart.getData(this);
            });
            this.buttonCheckout.click(function () {
                objCart.checkOutUser();
            });
            this.removeItemButton.click(function () {
                objCart.removeItemFromCart(this);
            });
            this.inputQtyChange.change(function () {
                objCart.inputChangeOnCart(this);
            });
        },

        /**
         * Function to get Data and perform add to cart action
         * @param {*} objThis
         */
        getData: function (objThis) {
            var intProductId = parseInt($(objThis).data("id"));
            var intQuantity = parseInt(
                $("#" + intProductId + "-quantity").val()
            );
            if (intProductId > 0 && intQuantity > 0) {
                objCart.throwSpinner(objThis);
                objCart.addToCart(intProductId, intQuantity, objThis);
            } else {
                alert("Quantity must be greater than 0");
            }
        },

        /**
         * Function to change button text to spinner
         * @param {*} objThis
         */
        throwSpinner: function (objThis) {
            $(objThis).html(
                '<i class="fa-spin fa fa-cog" aria-hidden="true"></i>'
            );
        },

        /**
         * Add to Cart
         */
        addToCart: function (intProductId, intQuantity, objThis) {
            var objData = {
                action: "add-product-to-cart",
                post_id: intProductId,
                quantity: intQuantity,
            };
            $.getJSON(ajaxurl, objData, function (response) {
                if (response) {
                    objCart.buttonAddToCart.html("Add To Cart");
                    if (response.success_message.length > 0) {
                        $(".hide-button").removeClass("hide-button");
                        $(".after-ajax-call-message").html(
                            response.success_message
                        );
                        $(".after-ajax-call-message").show();
                        $("#single-product-points-" + response.product_id).text(
                            response.product_points
                        );
                        objCart.updateCartMeta(
                            response.total_products,
                            response.total_points
                        );
                    }
                    if (
                        response.error_string.length > 0 &&
                        response.error == true
                    ) {
                        $(".after-ajax-call-message").html(
                            response.error_string
                        );
                        $(".after-ajax-call-message").show();
                    }
                }
            });
        },

        /**
         * Remove Item from cart
         *
         */
        removeItemFromCart: function (objThis) {
            var intProductId = parseInt($(objThis).data("id"));
            if (intProductId > 0) {
                var objData = {
                    action: "remove_item_from_cart",
                    product_id: intProductId,
                };
                $.getJSON(ajaxurl, objData, function (response) {
                    if (response.success == true) {
                        $(".after-ajax-call-message").html(response.message);
                        $("#remove-" + response.product_id).remove();
                        objCart.updateCartMeta(
                            response.total_products,
                            response.total_points
                        );
                    }
                });
            }
        },

        /**
         * On Input Change
         * @param {*} objThis
         */
        inputChangeOnCart: function (objThis) {
            var intProductId = parseInt($(objThis).attr("id"));
            var intQuantity = parseInt($(objThis).val());
            this.addToCart(intProductId, intQuantity, objThis);
        },

        /**
         * Update Cart Meta On Cart Page
         * @param {*} intUpdatedProducts
         * @param {*} intUpdatedPoints
         */
        updateCartMeta: function (intUpdatedProducts, intUpdatedPoints) {
            if ($("#cart-meta-products").length) {
                $("#cart-meta-products").text(intUpdatedProducts);
            }
            if ($("#cart-meta-points").length) {
                $("#cart-meta-points").text(intUpdatedPoints);
            }
        },

        /**
         *
         */
        checkOutUser: function () {
            objCart.buttonCheckout.hide();
            var userFirst = localStorage.getItem("first-name");
            var lastName = localStorage.getItem("last-name");
            var userPoints = localStorage.getItem("points");
            var userEmail = localStorage.getItem("email");

            var objData = {
                action: "check_out_user",
                firstName: userFirst,
                lastName: lastName,
                userPoints: userPoints,
                userEmail: userEmail,
            };

            $.getJSON(ajaxurl, objData, function (response) {
                if ($(".user-meta-points").length) {
                    $(".user-meta-points").html(
                        "<strong>Points: </strong> " + response.updated_points
                    );
                }
                if (response.success == true) {
                    var url =
                        "https://survey-api.npit.at/api/PointsHistory/Save";

                    var objPostRequest = new XMLHttpRequest();
                    objPostRequest.open("POST", url);

                    objPostRequest.setRequestHeader(
                        "Authorization",
                        "Bearer 9da12e9d-76a2-4c55-a4ae-ce97fcfda8c1"
                    );
                    objPostRequest.setRequestHeader(
                        "Content-Type",
                        "application/json"
                    );

                    objPostRequest.onreadystatechange = function () {
                        if (objPostRequest.readyState === 4) {
                            console.log(objPostRequest.status);
                            console.log(objPostRequest.responseText);
                        }
                    };

                    var data =
                        `{
                          "Points": ` +
                        -response.points +
                        `,
                        "Type": "earned"
                    }`;
                    objPostRequest.send(data);
                } else {
                    $(".after-ajax-call-message").html(response.message);
                }
            });
        },
    };
    objCart.initCart();
});
