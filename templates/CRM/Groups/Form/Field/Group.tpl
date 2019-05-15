<table style="display: none;">
  <tr id="customGroupRow">
    <td class="label">
      {$form.$groupInputName.label}
    </td>
    <td class="view-value">
      {$form.$groupInputName.html}
    </td>
  </tr>
</table>

{capture assign="isShowBillingBlock"}{if $context eq 'standalone' and $outBound_option != 2 && $showFeeBlock}1{else}0{/if}{/capture}

{literal}
<script type="text/javascript">
  (function() {

    var $form = CRM.$("form.{/literal}{$form.formClass}{literal}");
    var isShowBillingBlock = {/literal}{$isShowBillingBlock}{literal};
    var insertAfterSelector = '{/literal}{$insertAfterSelector}{literal}' || '.crm-participant-form-contact-id:first';
    var groupSelector = '{/literal}{$groupSelector}{literal}' || '#contact_group';
    var targetInputSelector = '{/literal}{$targetInputSelector}{literal}' || '#contact_id';

    CRM.$(document).ready(function () {
      moveGroupRow();
      initGroup();

      if (isShowBillingBlock) {
        handleEmail();
        validateEmail();
        CRM.$($form).on("change", '#send_receipt', validateEmail);
      }
    });

    function moveGroupRow() {
      var groupRowElement = CRM.$('#customGroupRow');
      groupRowElement.insertAfter(CRM.$(insertAfterSelector));
    }

    function initGroup() {
      CRM.$(groupSelector).on("change", groupInputOnChange);
      CRM.$(targetInputSelector).on("change", function () {
        CRM.$(groupSelector).select2("data", '');

        if (isShowBillingBlock) {
          handleEmail();
          validateEmail();
        }
      });
    }

    function groupInputOnChange() {
      CRM.$(targetInputSelector).select2("data", '');
      var groupIdList = CRM.$(groupSelector).select2("val");
      frozenFields();

      CRM.api3('CustomGroupContact', 'get_groups_contacts', {
        "group_id": groupIdList
      }).done(function(result) {
        ajaxCallback(result);

        if (isShowBillingBlock) {
          handleEmail();
          validateEmail();
        }
      });
    }

    function ajaxCallback(result) {
      if (result['is_error'] === 0 && result['values']) {
        var selectData = [];
        var count = 0;

        CRM.$.each(result['values'], function (index, value) {
          var item = {};
          item.id = value['contact_id'];
          item.label = value['display_name'];
          item.extra = {};
          item.extra.email = value['email'];
          selectData.push(item);
          count++;
        });

        if (count >= 2 && CRM.$('.crm-is-multi-activity-wrapper').length >= 1) {
          CRM.$('.crm-is-multi-activity-wrapper').show();
        } else {
          CRM.$('.crm-is-multi-activity-wrapper').hide();
        }

        CRM.$(targetInputSelector).select2("data", selectData);
      }

      unFrozenFields();
    }

    function frozenFields() {
      CRM.$(groupSelector).select2("readonly", true);
      CRM.$(targetInputSelector).select2("readonly", true);
    }

    function unFrozenFields() {
      CRM.$(groupSelector).select2("readonly", false);
      CRM.$(targetInputSelector).select2("readonly", false);
    }

    function handleEmail() {
      var data = CRM.$(targetInputSelector, $form).select2('data');

      if (data.length) {
        var emails = [];
        data.forEach(function(item) {
          if (item.extra.email && item.extra.email.length) {
            emails.push(item.extra.email);
          }
        });

        if (emails.length) {
          CRM.$("#email-receipt", $form).show();
          if (CRM.$("#send_receipt", $form).is(':checked')) {
            CRM.$("#notice", $form).show();
          }
          CRM.$("#email-address", $form).html(emails.join(', '));
          return;
        }
      }
      CRM.$("#email-receipt, #notice", $form).hide();
    }

    function validateEmail() {
      var title = ts('Warning'),
          msg = '';

      if (CRM.$('#send_receipt', $form).is(':checked') && CRM.$("#email-receipt").is(':visible')) {
        var data = CRM.$(targetInputSelector, $form).select2('data');

        if (data.length) {
          var contactWithoutEmails = [];
          data.forEach(function(item) {
            if (item.extra.email.length < 1) {
              contactWithoutEmails.push(
                '<a href="' + CRM.url('civicrm/contact/add', {reset: 1, action: 'update', cid: item.id}) + '">' + item.label + '</a>'
              );
            }
          });

          if (contactWithoutEmails.length) {
            closeNofify();
            msg = ts('Please specify a valid email address.').slice(0, -1) + ': ' + contactWithoutEmails.join(', ') + '.';
            CRM.alert(msg, title, 'warning');
            return;
          }
        }
      }

      closeNofify();

      function closeNofify() {
        CRM.$('#crm-notification-container .ui-notify-message').each(function () {
          if (title === CRM.$('h1', this).html()) {
            CRM.$('.icon.ui-notify-close', this).click();
          }
        });
      }
    }

  })();
</script>
{/literal}
