jQuery(document).ready(function($){
  // Add libero support to checkboxes
  $(".sp-data-table tbody tr td input:checkbox").on("updateCheckboxState", function(event, state) {
    switch (state) {
      case "L":
        $(this).data("checked", "L").prop("checked", false).prop("indeterminate", true).prop("readonly", false).val("").siblings("input:hidden").prop("disabled", false).val("L");
        break;
      case 0:
        $(this).data("checked", 0).prop("checked", false).prop("indeterminate", false).prop("readonly", false).val(0).siblings("input:hidden").prop("disabled", false).val(0);
        break;
      case -1:
        $(this).data("checked", -1).prop("checked", false).prop("indeterminate", false).prop("readonly", true).val("").siblings("input:hidden").prop("disabled", true).val(0);
        break;
      default:
        $(this).data("checked", 1).prop("checked", true).prop("indeterminate", false).prop("readonly", false).val(1).siblings("input:hidden").prop("disabled", false).val(0);
    }
  });

  $(".sp-data-table tbody tr td input:checkbox").each(function() {
    state = $(this).data("value");
    if ("" === state) {
      state = -1;
    }
    $(this).trigger("updateCheckboxState", state);
  });

  $(".sp-data-table tbody tr td input:checkbox").change(function() {
    switch ($(this).data("checked")) {
      case 1:
        state = 0;
        break;
      case 0:
        state = "L";
        break;
      case "L":
        state = -1;
        break;
      default:
        state = 1;
    }

    $(this).trigger("updateCheckboxState", state);
  });
});