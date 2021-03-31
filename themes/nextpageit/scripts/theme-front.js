var objMenu = {
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
};

function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
document.getElementById("defaultOpen").click();
$(document).ready(function () {
  var objCart = {
    buttonAddToCart: $(".add-to-cart"),
    initCart: function () {
      this.buttonAddToCart.click(function () {
        var intProductId = parseInt($(this).data("id"));
        var intQuantity = parseInt($("#" + intProductId + "-quantity").val());
        if (intProductId > 0 && intQuantity > 0) {
          objCart.throwSpinner(this);
          objCart.addToCart(intProductId, intQuantity, this);
        } else {
          alert("Quantity must be greater than 0");
        }
      });
    },

    /**
     * Add Spinner
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
          $(".hide-button").removeClass("hide-button");
          $(".after-ajax-call-message").html(response.success_message);
          $(".after-ajax-call-message").show();
        }
      });
    },
  };
  objCart.initCart();
});
