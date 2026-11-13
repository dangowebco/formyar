/**
 * FormYar - Form Builder JavaScript
 * Handles admin panel form builder functionality
 */
(function ($) {
  "use strict";

  // Initialize Jalali Date Picker
  if (typeof jalaliDatepicker !== "undefined") {
    jalaliDatepicker.startWatch({
      minDate: "attr",
      maxDate: "attr",
      zIndex: 9999999,
      time: false,
      separatorChars: { between: " - " },
    });
  }

  // Toast Notification System
  /**
   * Display a toast notification.
   *
   * @param {string} message  The message to display.
   * @param {string} type     The type: 'success', 'error', 'warning', or 'info'.
   * @param {number} duration Optional. Duration in milliseconds. Default 4000.
   */
  function showToast(message, type, duration) {
    type = type || "success";
    duration = duration || 4000;

    // RTL detection.
    var isRTL = $("html").attr("dir") === "rtl" || $("body").hasClass("rtl");

    // Icons for each type (SVG).
    var icons = {
      success:
        '<svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>',
      error:
        '<svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>',
      warning:
        '<svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>',
      info: '<svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>',
    };

    // Create container if not exists.
    var $container = $("#fmyar-toast-container");
    if (!$container.length) {
      $container = $("<div>", {
        id: "fmyar-toast-container",
        css: {
          position: "fixed",
          bottom: "24px",
          [isRTL ? "left" : "right"]: "24px",
          zIndex: "99999",
          display: "flex",
          flexDirection: "column",
          gap: "10px",
          pointerEvents: "none",
          maxWidth: "380px",
        },
      }).appendTo("body");
    }

    // Build toast.
    var $toast = $("<div>", {
      class: "fmyar-toast fmyar-toast-" + type,
      role: "alert",
      "aria-live": "polite",
      css: {
        display: "flex",
        alignItems: "center",
        gap: "10px",
        padding: "14px 16px",
        background: "var(--fmyar-surface, #fff)",
        color: "var(--fmyar-text, #18181b)",
        borderRadius: "10px",
        fontSize: "14px",
        fontWeight: "500",
        boxShadow: "0 10px 25px rgba(0,0,0,0.15)",
        border: "1px solid var(--fmyar-border, #e8e8ea)",
        pointerEvents: "auto",
        opacity: "0",
        transform: isRTL ? "translateX(-20px)" : "translateX(20px)",
        transition: "all 300ms cubic-bezier(0.4, 0, 0.2, 1)",
        position: "relative",
        overflow: "hidden",
        minWidth: "280px",
      },
    });

    // Icon.
    var $icon = $("<span>", {
      class: "fmyar-toast-icon",
      html: icons[type] || icons.info,
      css: {
        width: "20px",
        height: "20px",
        flexShrink: "0",
        display: "flex",
        alignItems: "center",
        justifyContent: "center",
      },
    });

    // Message.
    var $message = $("<span>", {
      class: "fmyar-toast-message",
      text: message,
      css: {
        flex: "1",
        lineHeight: "1.5",
      },
    });

    // Close button.
    var $close = $("<button>", {
      type: "button",
      class: "fmyar-toast-close",
      html: "&times;",
      "aria-label": "Close",
      css: {
        background: "none",
        border: "none",
        color: "var(--fmyar-text-tertiary, #a1a1aa)",
        fontSize: "20px",
        lineHeight: "1",
        cursor: "pointer",
        padding: "0 4px",
        flexShrink: "0",
        transition: "color 150ms",
      },
    });

    // Assemble.
    $toast.append($icon, $message, $close);
    $container.append($toast);

    // Animate in.
    setTimeout(function () {
      $toast.css({
        opacity: "1",
        transform: "translateX(0)",
      });
    }, 10);

    // Auto-dismiss.
    var dismissTimer = setTimeout(function () {
      dismissToast($toast, isRTL);
    }, duration);

    // Close button click.
    $close.on("click", function () {
      clearTimeout(dismissTimer);
      dismissToast($toast, isRTL);
    });

    // Hover pauses the auto-dismiss.
    $toast
      .on("mouseenter", function () {
        clearTimeout(dismissTimer);
      })
      .on("mouseleave", function () {
        dismissTimer = setTimeout(function () {
          dismissToast($toast, isRTL);
        }, 2000);
      });

    /**
     * Dismiss a toast with animation.
     */
    function dismissToast($el, rtl) {
      $el.css({
        opacity: "0",
        transform: rtl ? "translateX(-20px)" : "translateX(20px)",
      });
      setTimeout(function () {
        $el.remove();
        // Remove container if empty.
        if ($container.children().length === 0) {
          $container.remove();
        }
      }, 300);
    }
  }

  // Modal: Show — Focus on first focusable element.
  window.showModal = function (modalId) {
    var $modal = $("#" + modalId);
    $modal.removeClass("fmyar-hidden");
    $("body").addClass("fmyar-modal-open");

    // Focus first focusable element inside modal.
    setTimeout(function () {
      var $firstFocusable = $modal
        .find(
          'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])',
        )
        .filter(":visible")
        .first();

      if ($firstFocusable.length) {
        $firstFocusable.focus();
      } else {
        $modal.find(".fmyar-modal-container").attr("tabindex", "-1").focus();
      }
    }, 100);
  };

  // Modal: Hide — Return focus to trigger.
  window.hideModal = function (modalId) {
    var $modal = $("#" + modalId);
    var $trigger = $("[data-modal-target]:focus");

    $modal.addClass("fmyar-hidden");
    $("body").removeClass("fmyar-modal-open");

    if ($trigger.length) {
      $trigger.focus();
    }
  };

  // Trap focus inside modal.
  $(document).on("keydown", function (e) {
    if (e.key !== "Tab") return;

    var $modal = $(".fmyar-modal-overlay:not(.fmyar-hidden)").first();
    if (!$modal.length) return;

    var $focusable = $modal
      .find(
        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])',
      )
      .filter(":visible");

    if (!$focusable.length) return;

    var firstEl = $focusable.first()[0];
    var lastEl = $focusable.last()[0];

    if (e.shiftKey && document.activeElement === firstEl) {
      e.preventDefault();
      lastEl.focus();
    } else if (!e.shiftKey && document.activeElement === lastEl) {
      e.preventDefault();
      firstEl.focus();
    }
  });

  // Close modal on Escape key
  $(document).on("keydown", function (e) {
    if (e.key === "Escape") {
      $(".fmyar-modal-overlay:not(.fmyar-hidden)").each(function () {
        hideModal($(this).attr("id"));
      });
    }
  });

  // Close modal with close button (X) or cancel button
  $(document).on(
    "click",
    ".fmyar-modal-close, .fmyar-modal-close-btn, .fmyar-modal-cancel",
    function () {
      var modal = $(this).closest(".fmyar-modal-overlay");
      if (modal.length) hideModal(modal.attr("id"));
    },
  );

  // Dynamic Modal Handler
  $(document).on("click", ".fmyar-dynamic-modal", function () {
    var target = $(this).data("modal-target");
    if (target === "form_new") loadNewFormModal();
    if (target === "field_types") loadFieldTypesModal();
    if (target === "template") showModal("fmyar-template-modal");
    if (target === "import") showModal("fmyar-import-modal");
  });

  // Load New Form Modal
  function loadNewFormModal() {
    $("#fmyar-modal-title").text(
      fmyar_admin.i18n.create_form || "Create New Form",
    );

    var modalContent = `
            <form id="fmyar_new_form" class="fmyar-modal-form">
                <div class="fmyar-form-group">
                    <label class="fmyar-form-label">${fmyar_admin.i18n.form_title || "Form Title"} <span class="fmyar-required">*</span></label>
                    <input type="text" name="title" class="fmyar-input-full" required autofocus>
                </div>
                <div class="fmyar-form-group">
                    <label class="fmyar-form-label">${fmyar_admin.i18n.description || "Description"} <span class="fmyar-form-hint">(${fmyar_admin.i18n.optional || "optional"})</span></label>
                    <textarea name="description" rows="3" class="fmyar-textarea" placeholder="${fmyar_admin.i18n.form_description_placeholder || "Brief description of this form..."}"></textarea>
                </div>
                <input type="hidden" name="type" value="custom">
            </form>`;

    $("#fmyar-modal-body").html(modalContent);
    $("#fmyar-modal-footer")
      .html(
        '<button type="button" class="fmyar-btn fmyar-btn-secondary fmyar-modal-close-btn">' +
          (fmyar_admin.i18n.cancel || "Cancel") +
          "</button>" +
          '<button type="button" id="fmyar-create-form-btn" class="fmyar-btn fmyar-btn-primary">' +
          (fmyar_admin.i18n.create_form || "Create Form") +
          "</button>",
      )
      .removeClass("fmyar-hidden");

    showModal("fmyar-dynamic-modal");
    setTimeout(function () {
      $('#fmyar_new_form input[name="title"]').focus();
    }, 300);
  }

  // Load Field Types Modal
  function loadFieldTypesModal() {
    $("#fmyar-modal-title").text(fmyar_admin.i18n.add_field || "Add Field");
    $("#fmyar-modal-body").html(
      '<div class="fmyar-text-center fmyar-py-4"><div class="fmyar-spinner"></div></div>',
    );
    $("#fmyar-modal-footer").addClass("fmyar-hidden");
    showModal("fmyar-dynamic-modal");

    $.ajax({
      url: fmyar_admin.ajax_url,
      type: "POST",
      data: { action: "fmyar_get_field_types_modal", nonce: fmyar_admin.nonce },
      success: function (response) {
        if (response.success && response.data.html) {
          $("#fmyar-modal-body").html(response.data.html);
        } else {
          showToast(fmyar_admin.i18n.error_loading_field_types, "error");
        }
      },
      error: function () {
        showToast(fmyar_admin.i18n.error_loading_field_types, "error");
      },
    });
  }

  // Create Form Button
  $(document).on("click", "#fmyar-create-form-btn", function () {
    var btn = $(this);
    var form = $("#fmyar_new_form");
    if (!form.length) return;

    var title = form.find('input[name="title"]').val().trim();
    if (!title) {
      showToast(
        fmyar_admin.i18n.title_required || "Form title is required.",
        "warning",
      );
      return;
    }

    // Show spinner
    var originalText = btn.text();
    btn
      .prop("disabled", true)
      .html(
        '<span class="fmyar-spinner fmyar-spinner-light"></span> ' +
          (fmyar_admin.i18n.creating || "Creating..."),
      );

    $.ajax({
      url: fmyar_admin.ajax_url,
      type: "POST",
      data: {
        action: "fmyar_create_form",
        title: title,
        description: form.find('textarea[name="description"]').val(),
        type: form.find('input[name="type"]').val(),
        nonce: fmyar_admin.nonce,
      },
      success: function (response) {
        if (response.success && response.data.redirect) {
          window.location.href = response.data.redirect;
        } else {
          showToast(response.data.msg || fmyar_admin.i18n.error, "error");
          btn.prop("disabled", false).text(originalText);
        }
      },
      error: function () {
        showToast(fmyar_admin.i18n.error, "error");
        btn.prop("disabled", false).text(originalText);
      },
    });
  });

  // Field Type Selection
  $(document).on("click", ".fmyar-field-type-option", function () {
    var fieldType = $(this).data("field-type");
    if (!fieldType) return;
    hideModal("fmyar-dynamic-modal");
    loadFieldSettingsModal(fieldType);
  });

  // Load Field Settings Modal
  function loadFieldSettingsModal(fieldType, fieldData) {
    if (!fieldType) return;

    $("#fmyar-field-settings-title").text(
      fieldData
        ? fmyar_admin.i18n.field_settings || "Field Settings"
        : fmyar_admin.i18n.add_field || "Add Field",
    );
    $("#fmyar-field-settings-body").html(
      '<div class="fmyar-text-center fmyar-py-8"><div class="fmyar-spinner"></div></div>',
    );
    $("#fmyar-field-settings-footer").removeClass("fmyar-hidden");
    showModal("fmyar-field-settings-modal");

    var ajaxData = {
      action: "fmyar_get_field_settings",
      field_type: fieldType,
      nonce: fmyar_admin.nonce,
    };
    if (fieldData) ajaxData.field_data = JSON.stringify(fieldData);

    $.ajax({
      url: fmyar_admin.ajax_url,
      type: "POST",
      data: ajaxData,
      success: function (response) {
        if (response.success && response.data.html) {
          $("#fmyar-field-settings-body").html(response.data.html);
        } else {
          showToast(
            (response.data && response.data.msg) ||
              "Error loading field settings",
            "error",
          );
        }
      },
      error: function () {
        showToast(fmyar_admin.i18n.error_loading_field_settings, "error");
      },
    });
  }

  // Save Field Settings
  $(document).on("click", "#fmyar-save-field-settings", function () {
    var $form = $("#fmyar-field-settings-form");
    if (!$form.length) return;

    var fieldType = $form.find('input[name="field_type"]').val();
    var fieldId = $form.find('input[name="field_id"]').val();
    var fieldOrder = $form.find('input[name="field_order"]').val() || 0;
    var config = {};

    $form.find('[name^="config["]').each(function () {
      var $input = $(this);
      var name = $input.attr("name");
      var match = name.match(/^config\[(.+?)\]$/);
      if (match)
        config[match[1]] = $input.is(":checkbox")
          ? $input.is(":checked")
          : $input.val();
      var nestedMatch = name.match(/^config\[(.+?)\]\[(\d+)\]\[(.+?)\]$/);
      if (nestedMatch) {
        if (!config[nestedMatch[1]]) config[nestedMatch[1]] = [];
        if (!config[nestedMatch[1]][nestedMatch[2]])
          config[nestedMatch[1]][nestedMatch[2]] = {};
        config[nestedMatch[1]][nestedMatch[2]][nestedMatch[3]] = $input.val();
      }
    });

    if (fieldId) {
      var $existingField = $(
        '#fmyar-fields-list .fmyar-field-item[data-field-id="' + fieldId + '"]',
      );
      if ($existingField.length) {
        $existingField.data("config", config);
        $existingField.attr("data-config", JSON.stringify(config));
        $existingField
          .find(".fmyar-field-item-label")
          .text(config.label || fmyar_admin.i18n.untitled_field);
        var $badge = $existingField.find(".fmyar-field-required-badge");
        if (config.required) {
          if (!$badge.length)
            $existingField
              .find(".fmyar-field-item-header")
              .append(
                '<span class="fmyar-field-required-badge">' +
                  fmyar_admin.i18n.required_badge +
                  "</span>",
              );
        } else {
          $badge.remove();
        }
      }
    } else {
      if (!fieldId) fieldId = "fmyar_" + Date.now();
      var $fieldItem = createFieldItem({
        id: fieldId,
        type: fieldType,
        order: fieldOrder,
        config: config,
      });
      $("#fmyar-empty-state").addClass("fmyar-hidden");
      $("#fmyar-fields-list").append($fieldItem);
    }

    hideModal("fmyar-field-settings-modal");
  });

  // Create Field Item HTML
  function createFieldItem(fieldData) {
    var label =
      (fieldData.config && fieldData.config.label) ||
      fmyar_admin.i18n.untitled_field;
    return $("<div>", {
      class: "fmyar-field-item",
      "data-field-id": fieldData.id,
      "data-type": fieldData.type,
      "data-config": JSON.stringify(fieldData.config || {}),
    }).html(
      '<div class="fmyar-field-drag-handle"><svg viewBox="0 0 20 20" fill="currentColor"><path d="M7 4a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm5 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM7 10a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm5 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM7 16a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm5 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/></svg></div>' +
        '<div class="fmyar-field-item-content"><div class="fmyar-field-item-header"><span class="fmyar-field-item-icon"><span class="dashicons dashicons-editor-textcolor"></span></span><div class="fmyar-field-item-info"><span class="fmyar-field-item-label">' +
        label +
        '</span><span class="fmyar-field-item-type">' +
        fieldData.type +
        "</span></div></div></div>" +
        '<div class="fmyar-field-item-actions">' +
        '<button type="button" class="fmyar-field-action fmyar-edit-field" data-field-id="' +
        fieldData.id +
        '"><svg viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg></button>' +
        '<button type="button" class="fmyar-field-action fmyar-duplicate-field" data-field-id="' +
        fieldData.id +
        '"><svg viewBox="0 0 20 20" fill="currentColor"><path d="M7 9a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9z"/><path d="M5 3a2 2 0 00-2 2v6a2 2 0 002 2V5h8a2 2 0 00-2-2H5z"/></svg></button>' +
        '<button type="button" class="fmyar-field-action fmyar-field-action-danger fmyar-delete-field" data-field-id="' +
        fieldData.id +
        '"><svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg></button>' +
        "</div>",
    );
  }

  // Add Field Button
  $(document).on(
    "click",
    "#fmyar-add-field, #fmyar-add-first-field",
    function (e) {
      e.preventDefault();
      loadFieldTypesModal();
    },
  );

  // Edit Field
  $(document).on("click", ".fmyar-edit-field", function (e) {
    e.preventDefault();
    e.stopPropagation();
    var $fieldItem = $(this).closest(".fmyar-field-item");
    var fieldType = $fieldItem.attr("data-type");
    var fieldId = $fieldItem.attr("data-field-id");
    var configStr = $fieldItem.attr("data-config");
    var config = {};
    if (configStr) {
      try {
        config = JSON.parse(configStr);
      } catch (e) {
        config = {};
      }
    }
    if (!fieldType) return;
    loadFieldSettingsModal(fieldType, {
      id: fieldId,
      type: fieldType,
      config: config,
      order: $fieldItem.index(),
    });
  });

  // Delete Field
  $(document).on("click", ".fmyar-delete-field", function () {
    if (!confirm(fmyar_admin.i18n.confirm_delete_field)) return;
    $(this).closest(".fmyar-field-item").remove();
    if ($("#fmyar-fields-list .fmyar-field-item").length === 0)
      $("#fmyar-empty-state").removeClass("fmyar-hidden");
  });

  // Duplicate Field
  $(document).on("click", ".fmyar-duplicate-field", function () {
    var clone = $(this).closest(".fmyar-field-item").clone();
    var newId = "fmyar_" + Date.now();
    clone
      .attr("data-field-id", newId)
      .find("[data-field-id]")
      .attr("data-field-id", newId);
    $(this).closest(".fmyar-field-item").after(clone);
    $("#fmyar-empty-state").addClass("fmyar-hidden");
  });

  // Dynamic Options (Select/Radio/Checkbox)
  $(document).on("click", ".fmyar-add-option", function (e) {
    e.preventDefault();
    var container = $(this)
      .closest(".fmyar-field-settings")
      .find(".fmyar-options-container");
    if (!container.length) return;
    var index = container.children().length;
    var row = $("<div>", { class: "fmyar-option-row" }).html(
      '<input type="text" name="config[options][' +
        index +
        '][label]" placeholder="' +
        fmyar_admin.i18n.option_label +
        '" class="fmyar-input-full">' +
        '<input type="text" name="config[options][' +
        index +
        '][value]" placeholder="' +
        fmyar_admin.i18n.option_value +
        '" class="fmyar-input-full" dir="ltr">' +
        '<button type="button" class="fmyar-remove-option fmyar-action-btn fmyar-action-btn-danger"><svg class="fmyar-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>',
    );
    container.append(row);
  });

  $(document).on("click", ".fmyar-remove-option", function (e) {
    e.preventDefault();
    var row = $(this).closest(".fmyar-option-row");
    if (row.closest(".fmyar-options-container").children().length > 1)
      row.remove();
  });

  // Sortable Fields
  if ($.fn.sortable && $("#fmyar-fields-list").length) {
    $("#fmyar-fields-list").sortable({
      handle: ".fmyar-field-drag-handle",
      placeholder: "fmyar-field-item fmyar-field-placeholder",
      update: function () {
        $("#fmyar-fields-list .fmyar-field-item").each(function (index) {
          $(this).data("order", index);
        });
      },
    });
  }

  // Tabs System
  $(document).on("click", ".fmyar-tab", function (e) {
    e.preventDefault();
    var $tab = $(this);
    var tab = $tab.data("tab");
    var group = $tab.data("tab-group");
    if (!tab || !group) return;

    // Remove active from all tabs.
    $('[data-tab-group="' + group + '"].fmyar-tab')
      .removeClass("fmyar-tab-active")
      .attr("aria-selected", "false");

    // Add active to current tab.
    $tab.addClass("fmyar-tab-active").attr("aria-selected", "true");

    // Hide all panels.
    $('[data-tab-group="' + group + '"].fmyar-tab-panel').addClass(
      "fmyar-hidden",
    );

    // Show current panel.
    var $panel = $("#tab-" + group + "-" + tab);
    if ($panel.length) {
      $panel.removeClass("fmyar-hidden");
      // Focus the panel for screen readers.
      $panel.attr("tabindex", "-1").focus();
    }

    if (tab === "entries") loadFormEntries(1);
  });

  // Keyboard navigation for tabs.
  $(document).on("keydown", ".fmyar-tab", function (e) {
    var $tab = $(this);
    var group = $tab.data("tab-group");
    var $tabs = $('[data-tab-group="' + group + '"].fmyar-tab');
    var index = $tabs.index($tab);

    if (e.key === "ArrowRight" || e.key === "ArrowLeft") {
      e.preventDefault();
      var next =
        e.key === "ArrowRight"
          ? (index + 1) % $tabs.length
          : (index - 1 + $tabs.length) % $tabs.length;
      $tabs.eq(next).focus().click();
    } else if (e.key === "Home") {
      e.preventDefault();
      $tabs.first().focus().click();
    } else if (e.key === "End") {
      e.preventDefault();
      $tabs.last().focus().click();
    }
  });

  // Toggle Visibility
  $(document).on("change", ".fmyar-toggle-visibility", function () {
    var target = $(this).data("toggle-target");
    $(target).toggleClass("fmyar-hidden", !$(this).is(":checked"));
  });

  // Load Form Entries
  function loadFormEntries(page) {
    var formId = $(".fmyar-form-builder-container").data("entity-id");
    if (!formId) {
      var urlParams = new URLSearchParams(window.location.search);
      formId = urlParams.get("id");
    }
    if (!formId) return;

    page = page || 1;
    $("#fmyar-form-entries-list").html(
      '<tr><td colspan="4" class="fmyar-table-loading"><div class="fmyar-spinner"></div>' +
        fmyar_admin.i18n.loading_entries +
        "</td></tr>",
    );

    $.ajax({
      url: fmyar_admin.ajax_url,
      type: "POST",
      data: {
        action: "fmyar_get_form_entries",
        form_id: formId,
        page: page,
        nonce: fmyar_admin.nonce,
      },
      success: function (response) {
        if (
          response.success &&
          response.data.html &&
          response.data.html.trim() !== ""
        ) {
          $("#fmyar-form-entries-list").html(response.data.html);
          $("#fmyar-form-entries-empty").addClass("fmyar-hidden");
          $("#fmyar-form-entries-pagination").html(
            response.data.pagination || "",
          );
        } else {
          $("#fmyar-form-entries-list").html("");
          $("#fmyar-form-entries-empty").removeClass("fmyar-hidden");
          $("#fmyar-form-entries-pagination").html("");
        }
      },
      error: function () {
        $("#fmyar-form-entries-list").html("");
        $("#fmyar-form-entries-empty").removeClass("fmyar-hidden");
      },
    });
  }

  $(document).on("click", ".fmyar-form-entry-page", function (e) {
    e.preventDefault();
    loadFormEntries($(this).data("page"));
  });

  $(document).on("click", ".fmyar-view-form-entry", function () {
    $.ajax({
      url: fmyar_admin.ajax_url,
      type: "POST",
      data: {
        action: "fmyar_get_form_entry_details",
        entry_id: $(this).data("entry-id"),
        nonce: fmyar_admin.nonce,
      },
      success: function (response) {
        if (response.success) {
          $("#fmyar-entry-details-body").html(response.data.html);
          showModal("fmyar-entry-details-modal");
        }
      },
    });
  });

  // Delete Form
  $(document).on("click", ".fmyar-delete-form", function (e) {
    e.preventDefault();
    e.stopPropagation();
    var $btn = $(this);
    var formId = $btn.data("form-id");
    if (!formId) return;
    if (!confirm(fmyar_admin.i18n.confirm_delete_form || "Are you sure?"))
      return;

    $btn.prop("disabled", true);
    $.ajax({
      url: fmyar_admin.ajax_url,
      type: "POST",
      data: {
        action: "fmyar_delete_form",
        form_id: formId,
        nonce: fmyar_admin.nonce,
      },
      success: function (response) {
        if (response.success) {
          $btn.closest(".fmyar-form-card").fadeOut(300, function () {
            $(this).remove();
            if ($(".fmyar-form-card").length === 0) location.reload();
          });
          showToast(response.data.msg || "Form deleted", "success");
        } else {
          showToast(response.data.msg || "Error", "error");
          $btn.prop("disabled", false);
        }
      },
      error: function () {
        showToast(fmyar_admin.i18n.error_deleting_form, "error");
        $btn.prop("disabled", false);
      },
    });
  });

  // Delete Entry
  $(document).on("click", ".fmyar-delete-form-entry", function () {
    if (!confirm(fmyar_admin.i18n.confirm_delete_entry || "Are you sure?"))
      return;
    var $btn = $(this);
    var entryId = $btn.data("entry-id");
    $btn.prop("disabled", true);

    $.ajax({
      url: fmyar_admin.ajax_url,
      type: "POST",
      data: {
        action: "fmyar_delete_form_entry",
        entry_id: entryId,
        nonce: fmyar_admin.nonce,
      },
      success: function (response) {
        if (response.success) {
          $btn.closest("tr").fadeOut(300, function () {
            $(this).remove();
            if ($("#fmyar-form-entries-list tr").length === 0)
              loadFormEntries(1);
          });
          showToast(response.data.msg || "Entry deleted", "success");
        } else {
          showToast(response.data.msg || "Error", "error");
          $btn.prop("disabled", false);
        }
      },
      error: function () {
        showToast(fmyar_admin.i18n.error_deleting_entry, "error");
        $btn.prop("disabled", false);
      },
    });
  });

  // Delete File
  $(document).on("click", ".fmyar-delete-file", function () {
    if (!confirm(fmyar_admin.i18n.confirm_delete_file)) return;
    var $btn = $(this);
    var fileName = $btn.data("file");
    var $row = $btn.closest("tr");
    $btn.prop("disabled", true);

    $.ajax({
      url: fmyar_admin.ajax_url,
      type: "POST",
      data: {
        action: "fmyar_delete_file",
        file: fileName,
        nonce: fmyar_admin.nonce,
      },
      success: function (response) {
        if (response.success) {
          $row.fadeOut(300, function () {
            $row.remove();
            if ($(".fmyar-table-modern tbody tr").length === 0)
              location.reload();
          });
          showToast(response.data.msg || "File deleted", "success");
        } else {
          showToast(response.data.msg || "Error", "error");
          $btn.prop("disabled", false);
        }
      },
      error: function () {
        showToast(fmyar_admin.i18n.error_deleting_file, "error");
        $btn.prop("disabled", false);
      },
    });
  });

  // Export Entries
  $(document).on("click", "#fmyar-export-form-entries", function () {
    var formId = $(".fmyar-form-builder-container").data("entity-id");
    if (!formId) {
      var urlParams = new URLSearchParams(window.location.search);
      formId = urlParams.get("id");
    }
    if (!formId) {
      showToast(fmyar_admin.i18n.form_id_not_found, "error");
      return;
    }

    var exportUrl =
      fmyar_admin.ajax_url +
      "?action=fmyar_export_form_entries&form_id=" +
      formId +
      "&nonce=" +
      fmyar_admin.nonce;

    // Create hidden link and click it (avoids popup blockers).
    var $link = $("<a>", {
      href: exportUrl,
      target: "_blank",
      style: "display:none",
    }).appendTo("body");
    $link[0].click();
    $link.remove();
  });

  $(document).on("click", "#fmyar-refresh-form-entries", function () {
    loadFormEntries();
  });

  // Copy Shortcode
  $(document).on("click", ".fmyar-copy-btn", function (e) {
    e.preventDefault();
    e.stopPropagation();
    var $btn = $(this);
    var code =
      $btn.data("copy") ||
      $btn.siblings("code").text() ||
      $btn.closest(".fmyar-shortcode-box").find("code").text();
    if (!code) return;
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard
        .writeText(code)
        .then(function () {
          showToast(fmyar_admin.i18n.copied, "success");
          var orig = $btn.html();
          $btn.html(
            '<svg viewBox="0 0 20 20" fill="currentColor" style="width:15px;height:15px"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>',
          );
          setTimeout(function () {
            $btn.html(orig);
          }, 1500);
        })
        .catch(function () {
          showToast(fmyar_admin.i18n.copy_failed, "error");
        });
    } else {
      var textarea = document.createElement("textarea");
      textarea.value = code;
      textarea.style.cssText = "position:fixed;opacity:0";
      document.body.appendChild(textarea);
      textarea.select();
      try {
        document.execCommand("copy");
        showToast(fmyar_admin.i18n.copied, "success");
      } catch (err) {
        showToast(fmyar_admin.i18n.copy_failed, "error");
      }
      document.body.removeChild(textarea);
    }
  });

  // Save Form Button - click on header button
  $(document).on("click", "#fmyar-edit-form-btn", function (e) {
    e.preventDefault();

    var $btn = $(this);
    var formId = new URLSearchParams(window.location.search).get("id");

    if (!formId) {
      showToast(fmyar_admin.i18n.form_id_not_found, "error");
      return;
    }

    // Collect fields from Builder tab
    var fields = [];
    $("#fmyar-fields-list .fmyar-field-item").each(function (index) {
      var $item = $(this);
      var config = $item.data("config") || {};
      if (typeof config === "string") {
        try {
          config = JSON.parse(config);
        } catch (e) {
          config = {};
        }
      }
      fields.push({
        id: $item.data("field-id") || $item.attr("data-field-id"),
        type: $item.data("type") || $item.attr("data-type"),
        order: index,
        config: config,
      });
    });

    // Collect settings from Settings tab (inputs with form="fmyar-edit-form")
    var settings = {};
    $('[form="fmyar-edit-form"][name^="settings["]').each(function () {
      var $input = $(this);
      var name = $input.attr("name");
      var value;

      if ($input.attr("type") === "checkbox") {
        value = $input.is(":checked") ? "1" : "0";
      } else {
        value = $input.val();
      }

      var match = name.match(/^settings\[(.+?)\]\[(.+?)\]$/);
      if (match) {
        if (!settings[match[1]]) settings[match[1]] = {};
        settings[match[1]][match[2]] = value;
      }

      var deepMatch = name.match(/^settings\[(.+?)\]\[(.+?)\]\[(.+?)\]$/);
      if (deepMatch) {
        if (!settings[deepMatch[1]]) settings[deepMatch[1]] = {};
        if (!settings[deepMatch[1]][deepMatch[2]])
          settings[deepMatch[1]][deepMatch[2]] = {};
        settings[deepMatch[1]][deepMatch[2]][deepMatch[3]] = value;
      }
    });

    // Get title and description from Settings tab
    var title = $('[form="fmyar-edit-form"][name="title"]').val() || "";
    var description =
      $('[form="fmyar-edit-form"][name="description"]').val() || "";
    var status = $("#status-input").val() || "disabled";

    // Loading state
    var originalHTML = $btn.html();
    $btn
      .prop("disabled", true)
      .html(
        '<span class="fmyar-spinner fmyar-spinner-light"></span> ' +
          fmyar_admin.i18n.saving,
      );

    $.ajax({
      url: fmyar_admin.ajax_url,
      type: "POST",
      data: {
        action: "fmyar_admin_edit_form",
        form_id: formId,
        title: title,
        description: description,
        status: status,
        form_structure: JSON.stringify({ version: "1.0", fields: fields }),
        settings: JSON.stringify(settings),
        nonce: fmyar_admin.edit_nonce,
      },
      success: function (response) {
        if (response.success) {
          showToast(response.data.msg || "Saved!", "success");
        } else {
          showToast(response.data.msg || "Error", "error");
        }
      },
      error: function () {
        showToast(fmyar_admin.i18n.error_saving_form, "error");
      },
      complete: function () {
        $btn.prop("disabled", false).html(originalHTML);
      },
    });
  });

  // Save Settings
  $(document).on("submit", "#fmyar-settings-form", function (e) {
    e.preventDefault();

    var $form = $(this);
    var $btn = $form.find('button[type="submit"]');
    var $toggles = $form.find(".fmyar-toggle-checkbox");

    var data = {
      action: "fmyar_save_settings",
      nonce: $form.find("#fmyar_settings_nonce").val(),
      fmyar_recaptcha_enabled: $toggles.eq(0).is(":checked") ? "1" : "0",
      fmyar_recaptcha_site_key: $form
        .find('[name="fmyar_recaptcha_site_key"]')
        .val(),
      fmyar_recaptcha_secret_key: $form
        .find('[name="fmyar_recaptcha_secret_key"]')
        .val(),
      fmyar_delete_data: $toggles.eq(1).is(":checked") ? "1" : "0",
    };

    var originalHTML = $btn.html();
    $btn
      .prop("disabled", true)
      .html(
        '<span class="fmyar-spinner fmyar-spinner-light"></span> ' +
          fmyar_admin.i18n.saving,
      );

    $.ajax({
      url: fmyar_admin.ajax_url,
      type: "POST",
      data: data,
      success: function (response) {
        if (response.success) {
          showToast(response.data.msg || "Settings saved", "success");
        } else {
          showToast(response.data.msg || "Error", "error");
        }
      },
      error: function () {
        showToast(fmyar_admin.i18n.error_saving_settings, "error");
      },
      complete: function () {
        $btn.prop("disabled", false).html(originalHTML);
      },
    });
  });

  // When Select All is clicked.
  $(document).on("change", "#select-all-forms", function () {
    var isChecked = $(this).prop("checked");
    $('input[name="selected[]"]').prop("checked", isChecked);
    updateBulkCount();
  });

  // When individual checkbox is clicked.
  $(document).on("change", 'input[name="selected[]"]', function () {
    var total = $('input[name="selected[]"]').length;
    var checked = $('input[name="selected[]"]:checked').length;
    var $selectAll = $("#select-all-forms");

    if (checked === 0) {
      $selectAll.prop("checked", false).prop("indeterminate", false);
    } else if (checked === total) {
      $selectAll.prop("checked", true).prop("indeterminate", false);
    } else {
      $selectAll.prop("checked", false).prop("indeterminate", true);
    }

    updateBulkCount();
  });

  /**
   * Update the bulk action counter.
   */
  function updateBulkCount() {
    var checked = $('input[name="selected[]"]:checked').length;
    var $count = $("#fmyar-bulk-count");

    if (!$count.length) return;

    if (checked > 0) {
      $count
        .text(
          "(" + checked + " " + (fmyar_admin.i18n.selected || "selected") + ")",
        )
        .addClass("fmyar-bulk-count-visible");
    } else {
      $count.text("").removeClass("fmyar-bulk-count-visible");
    }
  }

  // Create form from template.
  $(document).on("click", ".fmyar-template-card", function () {
    var $btn = $(this);
    var template = $btn.data("template");
    if (!template) return;

    // Confirm.
    if (
      !confirm(
        fmyar_admin.i18n.confirm_template ||
          "Create a new form from this template?",
      )
    ) {
      return;
    }

    // Loading state.
    var originalHtml = $btn.html();
    $btn.prop("disabled", true).css("opacity", "0.6");

    $.ajax({
      url: fmyar_admin.ajax_url,
      type: "POST",
      data: {
        action: "fmyar_create_form_from_template",
        template: template,
        nonce: fmyar_admin.nonce,
      },
      success: function (response) {
        if (response.success && response.data.redirect) {
          window.location.href = response.data.redirect;
        } else {
          showToast(response.data.msg || "Error creating form", "error");
          $btn.prop("disabled", false).css("opacity", "1");
        }
      },
      error: function () {
        showToast(fmyar_admin.i18n.error_creating_form, "error");
        $btn.prop("disabled", false).css("opacity", "1");
      },
    });
  });
  // Dismiss admin notice via AJAX.
  $(document).on("click", ".fmyar-notice-close", function (e) {
    e.preventDefault();
    var $btn = $(this);
    var $notice = $btn.closest(".fmyar-notice");
    var dismissUrl = $btn.data("dismiss-url");

    if (!dismissUrl) return;

    $notice.addClass("is-dismissing");

    $.ajax({
      url: dismissUrl,
      type: "GET",
      success: function () {
        setTimeout(function () {
          $notice.remove();
        }, 300);
      },
      error: function () {
        // Fallback: navigate to the dismiss URL.
        window.location.href = dismissUrl;
      },
    });
  });

  // Duplicate Form.
  $(document).on("click", ".fmyar-duplicate-form", function (e) {
    e.preventDefault();
    e.stopPropagation();

    var $btn = $(this);
    var formId = $btn.data("form-id");

    if (!formId) return;

    if (
      !confirm(fmyar_admin.i18n.confirm_duplicate || "Duplicate this form?")
    ) {
      return;
    }

    var originalHtml = $btn.html();
    $btn.prop("disabled", true).css("opacity", "0.6");

    $.ajax({
      url: fmyar_admin.ajax_url,
      type: "POST",
      data: {
        action: "fmyar_duplicate_form",
        form_id: formId,
        nonce: fmyar_admin.nonce,
      },
      success: function (response) {
        if (response.success) {
          showToast(response.data.msg || "Form duplicated", "success");
          // Reload after short delay to show the new form.
          setTimeout(function () {
            window.location.reload();
          }, 800);
        } else {
          showToast(response.data.msg || "Error", "error");
          $btn.prop("disabled", false).css("opacity", "1").html(originalHtml);
        }
      },
      error: function () {
        showToast(fmyar_admin.i18n.error_duplicating_form, "error");
        $btn.prop("disabled", false).css("opacity", "1").html(originalHtml);
      },
    });
  });

  // Import Form.
  $(document).on("click", "#fmyar-do-import", function (e) {
    e.preventDefault();

    var $btn = $(this);
    var $fileInput = $("#fmyar-import-file");

    if (!$fileInput.length) {
      return;
    }

    var file = $fileInput[0].files[0];

    if (!file) {
      showToast(fmyar_admin.i18n.select_json_file, "warning");
      return;
    }

    if (file.size > 1024 * 1024) {
      showToast(fmyar_admin.i18n.file_too_large_1mb, "error");
      return;
    }

    if (!file.name.toLowerCase().endsWith(".json")) {
      showToast(fmyar_admin.i18n.upload_json_only, "warning");
      return;
    }

    if (!confirm(fmyar_admin.i18n.confirm_import || "Import this form?")) {
      return;
    }

    var formData = new FormData();
    formData.append("action", "fmyar_import_form");
    formData.append("import_file", file);
    formData.append("nonce", fmyar_admin.nonce);

    var originalHtml = $btn.html();
    $btn
      .prop("disabled", true)
      .html(
        '<span class="fmyar-spinner fmyar-spinner-light"></span> ' +
          fmyar_admin.i18n.importing,
      );

    $.ajax({
      url: fmyar_admin.ajax_url,
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        if (response.success) {
          // Close modal.
          hideModal("fmyar-import-modal");

          // Show success toast.
          showToast(response.data.msg || "Import completed", "success");

          // Reset file input.
          $("#fmyar-import-file").val("");

          // Restore button.
          $btn.prop("disabled", false).html(originalHtml);

          // Reload after short delay (to show new forms).
          setTimeout(function () {
            window.location.reload();
          }, 1500);
        } else {
          showToast(response.data.msg || "Error importing form", "error");
          $btn.prop("disabled", false).html(originalHtml);
        }
      },
      error: function (xhr, status, error) {
        showToast(fmyar_admin.i18n.error_importing_form, "error");
        $btn.prop("disabled", false).html(originalHtml);
      },
    });
  });

  /**
   * Add a new form card to the forms list.
   *
   * @param {object} form Form data from the server.
   */
  function addFormCardToList(form) {
    var $grid = $(".fmyar-forms-grid");

    // If grid doesn't exist (empty state), reload page.
    if (!$grid.length) {
      window.location.reload();
      return;
    }

    // Build the card HTML.
    var statusClass = form.is_active
      ? "fmyar-status-active"
      : "fmyar-status-inactive";
    var statusText = form.is_active
      ? fmyar_admin.i18n.active || "Active"
      : fmyar_admin.i18n.inactive || "Inactive";

    var cardHtml =
      '<div class="fmyar-form-card" data-form-id="' +
      form.id +
      '">' +
      '<label class="fmyar-form-card-check">' +
      '<input type="checkbox" name="selected[]" value="' +
      form.id +
      '" class="fmyar-checkbox-hidden">' +
      '<span class="fmyar-checkbox-custom"></span>' +
      "</label>" +
      '<a href="' +
      form.edit_url +
      '" class="fmyar-form-card-body-link">' +
      '<div class="fmyar-form-card-body">' +
      '<div class="fmyar-form-card-header">' +
      '<div class="fmyar-form-card-icon">' +
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">' +
      '<path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />' +
      "</svg>" +
      "</div>" +
      '<div class="fmyar-form-card-status">' +
      '<span class="fmyar-status-dot ' +
      statusClass +
      '"></span>' +
      '<span class="fmyar-status-text">' +
      statusText +
      "</span>" +
      "</div>" +
      "</div>" +
      '<h3 class="fmyar-form-card-title">' +
      escapeHtml(form.title) +
      "</h3>" +
      (form.description
        ? '<p class="fmyar-form-card-desc">' +
          escapeHtml(form.description) +
          "</p>"
        : "") +
      '<div class="fmyar-form-card-meta">' +
      '<span class="fmyar-form-card-stat">' +
      '<svg viewBox="0 0 20 20" fill="currentColor">' +
      '<path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />' +
      '<path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />' +
      "</svg>" +
      "0 " +
      (fmyar_admin.i18n.entries || "entries") +
      "</span>" +
      '<span class="fmyar-form-card-stat">' +
      '<svg viewBox="0 0 20 20" fill="currentColor">' +
      '<path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />' +
      "</svg>" +
      escapeHtml(form.created_at) +
      "</span>" +
      "</div>" +
      "</div>" +
      "</a>" +
      '<div class="fmyar-form-card-footer">' +
      '<code class="fmyar-shortcode">[fmyar_form id="' +
      form.id +
      '"]</code>' +
      '<div class="fmyar-form-card-actions">' +
      '<a href="' +
      form.edit_url +
      '" class="fmyar-btn-icon-sm" title="' +
      (fmyar_admin.i18n.edit || "Edit") +
      '">' +
      '<svg viewBox="0 0 20 20" fill="currentColor">' +
      '<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />' +
      "</svg>" +
      "</a>" +
      '<button type="button" class="fmyar-btn-icon-sm fmyar-duplicate-form" data-form-id="' +
      form.id +
      '" title="' +
      (fmyar_admin.i18n.duplicate || "Duplicate") +
      '">' +
      '<svg viewBox="0 0 20 20" fill="currentColor">' +
      '<path d="M7 9a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9z" />' +
      '<path d="M5 3a2 2 0 00-2 2v6a2 2 0 002 2V5h8a2 2 0 00-2-2H5z" />' +
      "</svg>" +
      "</button>" +
      '<button type="button" class="fmyar-btn-icon-sm fmyar-delete-form" data-form-id="' +
      form.id +
      '" title="' +
      (fmyar_admin.i18n.delete || "Delete") +
      '">' +
      '<svg viewBox="0 0 20 20" fill="currentColor">' +
      '<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />' +
      "</svg>" +
      "</button>" +
      "</div>" +
      "</div>" +
      "</div>";

    // Prepend the new card to the grid (with animation).
    var $newCard = $(cardHtml).css("opacity", "0").prependTo($grid);
    // Update stats counters.
    var currentTotal =
      parseInt($(".fmyar-stat-card:first .fmyar-stat-value").text().trim()) ||
      0;
    $(".fmyar-stat-card:first .fmyar-stat-value").text(currentTotal + 1);

    var currentInactive =
      parseInt(
        $(".fmyar-stat-card:nth-child(3) .fmyar-stat-value").text().trim(),
      ) || 0;
    $(".fmyar-stat-card:nth-child(3) .fmyar-stat-value").text(
      currentInactive + 1,
    );

    // Update filter tab counters.
    var $allCount = $(".fmyar-filter-tab:first .fmyar-filter-count");
    var allCount = parseInt($allCount.text().trim()) || 0;
    $allCount.text(allCount + 1);

    var $inactiveCount = $(
      ".fmyar-filter-tab:nth-child(3) .fmyar-filter-count",
    );
    var inactiveCount = parseInt($inactiveCount.text().trim()) || 0;
    $inactiveCount.text(inactiveCount + 1);

    setTimeout(function () {
      $newCard.css({
        opacity: "1",
        transition: "opacity 300ms ease",
      });
    }, 50);

    // Scroll to the new card.
    $("html, body").animate(
      {
        scrollTop: $newCard.offset().top - 100,
      },
      400,
    );
  }

  /**
   * Escape HTML special characters.
   *
   * @param {string} text Text to escape.
   * @returns {string} Escaped text.
   */
  function escapeHtml(text) {
    if (typeof text !== "string") return "";
    var div = document.createElement("div");
    div.appendChild(document.createTextNode(text));
    return div.innerHTML;
  }

  // Help Widget
  // Toggle help menu.
  $(document).on("click", "#fmyar-help-toggle", function (e) {
    e.preventDefault();
    e.stopPropagation();

    var $menu = $("#fmyar-help-menu");
    var $btn = $(this);
    var isOpen = !$menu.hasClass("fmyar-hidden");

    if (isOpen) {
      $menu.addClass("fmyar-hidden");
      $btn.attr("aria-expanded", "false");
      $btn.removeClass("is-active");
    } else {
      $menu.removeClass("fmyar-hidden");
      $btn.attr("aria-expanded", "true");
      $btn.addClass("is-active");
    }
  });

  // Close menu when clicking outside.
  $(document).on("click", function (e) {
    if (!$(e.target).closest("#fmyar-help-widget").length) {
      $("#fmyar-help-menu").addClass("fmyar-hidden");
      $("#fmyar-help-toggle")
        .attr("aria-expanded", "false")
        .removeClass("is-active");
    }
  });

  // Close menu on Escape.
  $(document).on("keydown", function (e) {
    if (e.key === "Escape") {
      var $menu = $("#fmyar-help-menu");
      if (!$menu.hasClass("fmyar-hidden")) {
        $menu.addClass("fmyar-hidden");
        $("#fmyar-help-toggle")
          .attr("aria-expanded", "false")
          .removeClass("is-active");
      }
    }
  });
})(jQuery);
