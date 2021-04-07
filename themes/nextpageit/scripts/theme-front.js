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
  getDataFromApi();
  // Init Data Table Cart
  if ($("#cart-table").length) {
    cartInit();
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
            objCart.showUserHeader(responseData);
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
      $(".account-info-main").show();
    }
  }

  var objCart = {
    buttonAddToCart: $(".add-to-cart"),
    buttonCheckout: $(".checkout-and-reedem"),
    removeItemButton: $(".remove-item-from-cart"),
    inputQtyChange: $(".cart-qty-change"),
    elementProductContainer: $(".product-main-container"),
    elementUpdatePoints: $("#user-total-points"),
    elementCartBottom: $(".cart-bottom-part"),
    elementCartBottomAnimation: $(".checkout-bottom-part-animation"),
    elementUserInfoContainer: $(".account-info-main"),
    apiUpdatePoints: "https://survey-api.npit.at/api/PointsHistory/Save",
    apiToken: "9da12e9d-76a2-4c55-a4ae-ce97fcfda8c1",
    elementAfterAjaxMessage: $(".after-ajax-call-message"),
    elementProductQuantity: $(".product-quantity"),
    elementShowPoints: $("#points-on-selection"),

    /**
     * Init Function
     */
    initCart: function () {
      this.buttonAddToCart.click(function () {
        objCart.getData(this);
      });
      if (this.elementProductContainer.length) {
        objCart.elementProductContainer.click(function () {
          objCart.goToSingleView(this);
        });
      }
      this.buttonCheckout.click(function () {
        objCart.checkOutUser();
      });
      this.removeItemButton.click(function () {
        objCart.removeItemFromCart(this);
      });
      this.inputQtyChange.change(function () {
        objCart.inputChangeOnCart(this);
      });
      this.elementProductQuantity.change(function () {
        objCart.showLivePoints(this);
      });
    },

    /**
     * Go to Single Product
     * @param {*} objThis
     */
    goToSingleView: function (objThis) {
      var permalink = $(objThis).data("permalink");
      if (permalink.length > 0) {
        window.location.href = permalink;
      }
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
     * Show User Header
     * @param {*} responseData
     */
    showUserHeader: function (responseData) {
      $(".user-meta-spinner").hide();
      $(".user-meta-name").html(
        '<i class="fa fa-user-circle" aria-hidden="true"></i> ' +
          responseData.data.firstName +
          " " +
          responseData.data.lastName
      );
      $("#user-total-points").html(responseData.data.pointBalance);
    },

    /**
     * Add Item to cart
     * @param {*} intProductId
     * @param {*} intQuantity
     * @param {*} objThis
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
            objCart.elementAfterAjaxMessage.html(response.success_message);
            objCart.elementAfterAjaxMessage.show();
            $("#single-product-points-" + response.product_id).text(
              response.product_points
            );
            objCart.updateCartMeta(
              response.total_products,
              response.total_points
            );
          }
          if (response.error_string.length > 0 && response.error == true) {
            objCart.elementAfterAjaxMessage.html(response.error_string);
            objCart.elementAfterAjaxMessage.show();
          }
        }
      });
    },

    /**
     * Change Points on user selection
     * @param {*} objThis
     */
    showLivePoints: function (objThis) {
      var intQty = $(objThis).val();
      if (intQty > 0) {
        var intPointPerProduct = $(objThis).data("product-point");
        this.elementShowPoints.text(intPointPerProduct * intQty + " Points");
      } else {
        this.elementShowPoints.text("");
      }
    },
    /**
     * Remove Item From Cart
     * @param {*} objThis
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
            objCart.elementAfterAjaxMessage.html(response.message);
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
     * CheckOut User
     */
    checkOutUser: function () {
      var userFirst = localStorage.getItem("first-name");
      var lastName = localStorage.getItem("last-name");
      var userPoints = localStorage.getItem("points");
      var userEmail = localStorage.getItem("email");

      if (userEmail.length > 0 && userFirst.length > 0) {
        objCart.checkOutShowAnimation("show");
        var objData = {
          action: "check_out_user",
          firstName: userFirst,
          lastName: lastName,
          userPoints: userPoints,
          userEmail: userEmail,
        };
      }

      $.getJSON(ajaxurl, objData, function (objResponse) {
        if (objResponse.success == true) {
          objCart.updateUserPoints(
            objResponse.updated_points,
            objResponse.points
          );
          objCart.updateCheckOutView(objResponse);
        } else {
          objCart.elementAfterAjaxMessage.html(objResponse.message);
          objCart.checkOutShowAnimation("hide");
        }
      });
    },

    /**
     * Update Check Out View
     * @param {*} objResponse
     */
    updateCheckOutView: function (objResponse) {
      this.elementAfterAjaxMessage.html(objResponse.message);
      this.elementCartBottom.remove();
      this.elementCartBottomAnimation.remove();
      this.elementUserInfoContainer.remove();
      this.buttonCheckout.remove();
    },
    /**
     * Animation on checkout button
     * @param {*} strType
     */
    checkOutShowAnimation: function (strType) {
      if (strType === "show") {
        this.elementCartBottom.hide();
        this.elementCartBottomAnimation.show();
        this.elementUserInfoContainer.hide();
        this.buttonCheckout.hide();
      } else {
        this.elementCartBottom.show();
        this.elementCartBottomAnimation.hide();
        this.elementUserInfoContainer.show();
        this.buttonCheckout.show();
      }
    },

    /**
     * Post Updated Points
     * @param {*} response
     */
    updateUserPoints: function (intUpdatedPoints, intPoints) {
      var objPostRequest = new XMLHttpRequest();
      objPostRequest.open("POST", objCart.apiUpdatePoints);
      objPostRequest.setRequestHeader(
        "Authorization",
        "Bearer " + objCart.apiToken
      );

      objPostRequest.setRequestHeader("Content-Type", "application/json");
      objCart.updateHeaderPoints(intUpdatedPoints);
      objPostRequest.send(objCart.getResponseObject(intPoints));
    },

    /**
     * Get Updated JSON Object To Post To API
     * @param {*} intPoints
     */
    getResponseObject: function (intPoints) {
      var data =
        `{
              "Points": ` +
        -intPoints +
        `,
                "Type": "earned"
            }`;
      return data;
    },

    /**
     * Update Points in the User Header
     * @param {*} intPoints
     */
    updateHeaderPoints: function (intPoints) {
      if (this.elementUpdatePoints.length) {
        this.elementUpdatePoints.html(intPoints);
      }
    },
  };
  objCart.initCart();
});
