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
            showUserHeader(responseData);
            showAccountInfo(responseData);
          }
        }
      }
    };
    objRequest.send();
  }

  /**
   * Show User Account Information
   */

  function showAccountInfo(responseData) {
    $(".spinner-container").hide();

    if ($("#account_first_name").length > 0) {
      $("#account_first_name").html(responseData.data.firstName);
    }
    if ($("#account_last_name").length > 0) {
      $("#account_last_name").html(responseData.data.lastName);
    }
    if ($("#account_response_id").length > 0) {
      $("#account_response_id").html(responseData.data.id);
    }
    if ($("#account_password").length > 0) {
      $("#account_password").html(responseData.data.password);
    }
    if ($("#account_email").length > 0) {
      $("#account_email").html(responseData.data.email);
    }
    if ($("#account_phone_number").length > 0) {
      $("#account_phone_number").html(responseData.data.pointBalance);
    }
    if ($("#account_user_points").length > 0) {
      $("#account_user_points").html(responseData.data.pointBalance);
    }
    if ($(".account-info-container").length > 0) {
      $(".account-info-container").show();
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
      tablinks[i].className = tablinks[i].className.replace(" active", "");
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
        var intProductId = parseInt($(this).data("id"));
        objCart.removeItemFromCart(intProductId);
      });
    },

    /**
     * Function to get Data and perform add to cart action
     * @param {*} objThis
     */
    getData: function (objThis) {
      var intProductId = parseInt($(objThis).data("id"));
      var intQuantity = parseInt($("#" + intProductId + "-quantity").val());
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
      $(objThis).html('<i class="fa-spin fa fa-cog" aria-hidden="true"></i>');
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
            $(".after-ajax-call-message").addClass("success");
            $(".after-ajax-call-message").removeClass("error");
            $(".after-ajax-call-message").html(response.success_message);
            $(".after-ajax-call-message").show();
          }
          if (response.error_string.length > 0 && response.error == true) {
            $(".after-ajax-call-message").html(response.error_string);
            $(".after-ajax-call-message").removeClass("success");
            $(".after-ajax-call-message").addClass("error");
            $(".after-ajax-call-message").show();
          }
        }
      });
    },

    /**
     * Remove Item from cart
     * @param {*} intProductId
     */
    removeItemFromCart: function (intProductId) {
      if (intProductId > 0) {
        var objData = {
          action: "remove_item_from_cart",
          product_id: intProductId,
        };
        $.getJSON(ajaxurl, objData, function (response) {
          if (response.success == true) {
            $("#remove-" + response.product_id).remove();
            objCart.updateCartMeta(
              response.total_products,
              response.total_points
            );
          }
        });
      }
    },
    updateCartMeta: function (intUpdatedProducts, intUpdatedPoints) {
      $("#cart-meta-products").text(intUpdatedProducts);
      $("#cart-meta-points").text(intUpdatedPoints);
    },

    /**
     *
     */
    checkOutUser: function () {
      objCart.buttonCheckout.hide();
      var objData = {
        action: "check_out_user",
      };

      $.getJSON(ajaxurl, objData, function (response) {
        if (response.success == true) {
          window.location.href =
            homeurl + "/thank-you?order_id=" + response.order_id;
        }
      });
    },
  };
  objCart.initCart();
});
