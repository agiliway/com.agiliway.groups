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

{literal}
<script type="text/javascript">
  (function() {

    var insertAfterSelector = '{/literal}{$insertAfterSelector}{literal}';
    var groupSelector = '{/literal}{$groupSelector}{literal}';
    var targetInputSelector = '{/literal}{$targetInputSelector}{literal}';

    CRM.$(document).ready(function () {
      moveGroupRow();
      initGroup();
    });

    function moveGroupRow() {
      var groupRowElement = CRM.$('#customGroupRow');
      groupRowElement.insertAfter(CRM.$(insertAfterSelector));
    }

    function initGroup() {
      CRM.$(groupSelector).on("change", groupInputOnChange);
      CRM.$(targetInputSelector).on("change", function () {
        CRM.$(groupSelector).select2("data", '');
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

  })();
</script>
{/literal}
