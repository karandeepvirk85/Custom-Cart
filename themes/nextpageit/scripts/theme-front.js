$(document).ready(function () {
  if ($("#cart-table").length) {
    cartInit();
  }

  $(".product-main-container").click(function () {
    goToSingleView(this);
  });

  function cartInit() {
    $("#cart-table").DataTable();
  }

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
            $(".after-ajax-call-message").html(response.success_message);
            $(".after-ajax-call-message").show();
          }
          if (response.error_string.length > 0 && response.error == true) {
            $(".after-ajax-call-message").html(response.error_string);
            $(".after-ajax-call-message").addClass("error");
            $(".after-ajax-call-message").show();
          }
        }
      });
    },

    /**
     * Checkout User
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
