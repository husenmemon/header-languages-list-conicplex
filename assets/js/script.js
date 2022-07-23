jQuery(document).ready(function ($) {
  $(document).scroll(function () {
    // Get document scroll lenght
    var document_scroll_lenght = $(document).scrollTop();

    // Get document width
    var document_width = $(document).width();

    // If document width more than 600 then execute the below code
    if (document_width > 600) {
      // If document scroll lenght more than 100 then execute the below code
      if (document_scroll_lenght >= 100) {
        $header_style =
          "box-shadow: 0px 0px 12px 0 rgb(0 0 0 / 25%) !important";
        $("header").attr("style", $header_style);
        $(".hlcp-main").css({
          "flex-direction": "column",
          "align-items": "unset",
          padding: "12px 5.75%",
          "box-shadow": "unset",
          "border-top": "1px solid #cbcbcb",
        });
        $(".hlcp-heading").hide();
        $(".hlcp-heading-on-scroll").show();
        $(".hlcp-lang-item").css({
          "flex-direction": "row",
          margin: "0",
          "margin-right": "12px",
          padding: "5px 8px",
          border: "solid 1px #cbcbcb",
        });
        $(".hlcp-country-flag").css({ margin: "0" });
        $(".hlcp-country-flag img").css({
          "min-width": "20px",
          height: "20px",
          border: "unset",
          "margin-right": "5px",
        });

        $(".hlcp-lang-show").show();
        $(".hlcp-lang-more").hide();
      } else {
        $header_style = "box-shadow: unset !important";
        $("header").attr("style", $header_style);
        $(".hlcp-main").css({
          "flex-direction": "row",
          "align-items": "center",
          padding: "10px 5.75%",
          "box-shadow": "rgba(0, 0, 0, 0.25) 0px 0px 7px 0px inset",
          "max-width": "59em",
          "border-top": "unset",
        });
        $(".hlcp-heading").show();
        $(".hlcp-heading-on-scroll").hide();
        $(".hlcp-lang-item").css({
          "flex-direction": "column",
          margin: "10px 21px",
          padding: "unset",
          border: "unset",
        });
        $(".hlcp-country-flag").css({ margin: "7px 0" });
        $(".hlcp-country-flag img").css({
          "min-width": "60px",
          height: "60px",
          border: "5px solid lightgray",
          "margin-right": "unset",
        });
        $(".hlcp-lang-show").hide();
        $(".hlcp-lang-more").show();
      }
    }
  });

  // Append html output in header
  $("header").append(hlcp_html_output);
});
