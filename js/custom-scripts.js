jQuery(function () {
  var bpm_question_container, result_container, action_container, bpm_max_answer, bpm_total_poll_options

  var btn_poll_flip = jQuery(".btn-poll-flip")

  jQuery(".bpm-poll-options").find('input[name="poll-option"]').prop("checked", false)

  btn_poll_flip.click(function () {
    var cache_btn_poll_flip = jQuery(this),
      poll_container_id = cache_btn_poll_flip.data("poll-container-id"),
      poll_container_status = cache_btn_poll_flip.data("status")

    bpm_question_container = jQuery("#bpm-question-" + poll_container_id)

    var bpm_poll_question_style = bpm_question_container.find(".bpm-poll-question").attr("style")

    result_container = jQuery("#bpm-result-" + poll_container_id)

    if (poll_container_status == 1) {
      //show voiting panel.
      cache_btn_poll_flip.data("status", "0").text(bpm_text_wait_msg).attr("disabled", "disabled")
      result_container.fadeOut("slow", function () {
        bpm_question_container.fadeIn("slow")
        cache_btn_poll_flip.data("status", "0").text(bpm_text_show_result).removeAttr("disabled")
        bpm_question_container.find('input[name="poll-option"]').prop("checked", false)
      })

      // set hide option in here.

      result_container.find(".chart").each(function (index) {
        var tipsy_content = jQuery(this)

        jQuery(this)
          .delay(100 * index)
          .fadeIn(500, function () {
            tipsy_content.tooltipster("destroy")
          })
      })

      // we need to remove disable attribute from vote now button.
      //            console.log("Prev"+cache_btn_poll_flip.prev('button.btn-poll-vote-now').length);
      cache_btn_poll_flip.prev("button.btn-poll-vote-now").removeAttr("disabled")
    } else {
      // Hide Voting Panel & show result Section

      cache_btn_poll_flip.data("status", "1").text(bpm_text_wait_msg).attr("disabled", "disabled")

      var poll_id = cache_btn_poll_flip.data("poll-id")
      var poll_bar_theme = cache_btn_poll_flip.data("theme")
      var poll_animate_class = cache_btn_poll_flip.data("animate-class")

      // Added disabled attribute to vote now button.
      //            console.log("Prev"+cache_btn_poll_flip.prev('button.btn-poll-vote-now').length);
      cache_btn_poll_flip.prev("button.btn-poll-vote-now").attr("disabled", "disabled")

      //            alert(" "+bpm_question_container.find('.bpm-poll-options').outerheight());

      var bpm_question_container_height = bpm_question_container.find(".bpm-poll-options").outerHeight()

      bpm_question_container.fadeOut("slow", function () {
        result_container.html('<p class="bpm-poll-results-title" style="' + bpm_poll_question_style + '"><i class="fa fa-bar-chart-o"></i> ' + bpm_text_poll_result + '</p><p class="bpm-wait-message-container"><img src="' + bpm_load_icon_url + '" alt="" /></p>')
        result_container.find(".bpm-wait-message-container").attr("style", " height : " + bpm_question_container_height + "px; line-height: " + bpm_question_container_height + "px;")

        result_container.fadeIn("slow", function () {
          cache_btn_poll_flip.data("status", "1").text(bpm_text_show_voting_panel).removeAttr("disabled")
          bpm_question_container.find('input[name="poll-option"]').prop("checked", false)
          pm_count_vote(poll_id, -1, poll_bar_theme, poll_animate_class)
          bpm_animate_bar()
        })
      })
    }
  })

  var bpm_poll_options = jQuery(".bpm-poll-options")
  var poll_option = jQuery(".poll-option")

  // radion button click event.

  poll_option.click(function () {
    var choose_id = jQuery(this).val(),
      poll_container_id = jQuery(this).data("poll-container-id"),
      poll_bar_theme = jQuery(this).data("theme"),
      poll_animate_class = jQuery(this).data("animate-class")

    bpm_question_container = jQuery("#bpm-question-" + poll_container_id)
    result_container = jQuery("#bpm-result-" + poll_container_id)
    action_container = jQuery("#action-container-" + poll_container_id)

    /*--- Start New Code For Multiple Answer  ---*/

    if (jQuery(this).attr("type") == "checkbox") {
      return ""
    }

    /*--- End New Code For Multiple Answer ---*/

    var bpm_poll_question_style = bpm_question_container.find(".bpm-poll-question").attr("style")

    var poll_id = jQuery(this).data("poll-id")

    var bpm_question_container_height = bpm_question_container.find(".bpm-poll-options").outerHeight()

    bpm_question_container.fadeOut("slow", function () {
      result_container.html('<p class="bpm-poll-results-title" style="' + bpm_poll_question_style + '"><i class="fa fa-bar-chart-o"></i> ' + bpm_text_poll_result + '</p><p class="bpm-wait-message-container"><img src="' + bpm_load_icon_url + '" alt="" /></p>')
      result_container.find(".bpm-wait-message-container").attr("style", " height : " + bpm_question_container_height + "px; line-height: " + bpm_question_container_height + "px;")

      result_container.fadeIn("slow", function () {
        pm_count_vote(poll_id, choose_id, poll_bar_theme, poll_animate_class)
        // change button status.
        action_container.find(".btn-poll-flip").data("status", "1").text(bpm_text_show_voting_panel)
        bpm_question_container.find('input[name="poll-option"]').prop("checked", false)
      })
    })
  })

  function bpm_animate_bar() {
    // Animate Bar

    var delay = 0
    result_container
      .find(".bar")
      .children("span")
      .each(function () {
        var bar_width = jQuery(this).data("bar_width")
        jQuery(this)
          .width(0)
          .delay(delay)
          .animate(
            {
              width: bar_width + "%",
            },
            1500
          )

        delay += 300
      })
  }

  function handle_server_vote_count(poll_id, choose_id) {
    return jQuery.ajax({
      url: ajaxurl,
      type: "POST",
      dataType: "JSON",
      data: {
        action: "bwl_pm_add_vote", // action will be the function name
        cast_vote: true,
        poll_id: poll_id,
        choose_id: choose_id,
      },
    })
  }

  function pm_count_vote(poll_id, choose_id, poll_bar_theme, poll_animate_class) {
    var poll_bar

    jQuery.when(handle_server_vote_count(poll_id, choose_id)).done(function (data) {
      if (data.status == 1) {
        var bpml_poll_result_text = ""

        //Fixed in version 1.0.4

        if (typeof data.msg != "undefined" && data.msg != "") {
          bpml_poll_result_text += '<div class="bpm-msg-container"><i class="fa fa-info-circle"></i>' + data.msg + "</div>"
        }
        //
        //                     console.log(data.msg);
        ////                            console.log(data.toSource());
        bpml_poll_result_text += '<p class="bpm-poll-results-title" style="background:' + data.bpm_ques_bg + "; color: " + data.bpm_ques_font_color + ';"><i class="fa fa-bar-chart-o"></i> ' + bpm_text_poll_result + "</p>"

        var bpml_poll_result = data.bpml_poll_result

        if (data.bpm_hide_poll_result == 2) {
          bpml_poll_result_text += '<p class="bpm-message"><i class="fa fa-info-circle"></i> ' + data.msg + "</p>"
        } else if (data.bpm_hide_poll_result == 1) {
          bpml_poll_result_text += '<p class="bpm-message"><i class="fa fa-info-circle"></i> ' + data.add_msg + "</p>"
        } else {
          var poll_total_votes = 0,
            chart_html = ""

          bpml_poll_result_text += '<ul class="bpm-poll-results">'

          jQuery.each(bpml_poll_result, function (index, result) {
            //                                    console.log(result.toSource());

            //                                    console.log(result.option_title.value);

            if (typeof result.option_title != "undefined") {
              poll_bar = '<div class="bar ' + poll_bar_theme + '" style="background:' + data.bpml_poll_bar_bg + '"><span class="' + poll_animate_class + '" style="width: ' + result.bar_width + '%; " data-bar_width = "' + result.bar_width + '"></span></div>'
              bpml_poll_result_text += "<li>" + result.option_title.value + " : " + result.total_votes + poll_bar + "</li>"

              poll_total_votes = parseInt(poll_total_votes) + parseInt(result.total_votes)

              chart_html += '<span class="chart" data-percent="' + result.bar_width + '" title="' + result.option_title.value + '">' + '<span class= "percent"></span>' + "</span>"
            }
          })

          bpml_poll_result_text += "</ul> <!-- end .bpm-poll-results  -->"

          if (data.bpm_result_display_type == "bpm_chart") {
            bpml_poll_result_text = chart_html
          }

          bpml_poll_result_text += '<p class="total-votes"><i class="fa fa-bar-chart-o"></i> ' + bpm_text_total_votes + ": " + poll_total_votes + "</p>"
        }

        if (data.bpm_result_display_type == "bpm_chart") {
          result_container
            .html(bpml_poll_result_text)
            .promise()
            .done(function () {
              var bpm_chart_barcolor = data.bpm_chart_barcolor,
                bpm_chart_trackcolor = data.bpm_chart_trackcolor,
                bpm_chart_scalecolor = data.bpm_chart_scalecolor,
                bpm_chart_linecap = data.bpm_chart_linecap,
                bpm_chart_linewidth = parseInt(data.bpm_chart_linewidth),
                bpm_chart_animate_time = parseInt(data.bpm_chart_animate_time)

              bpm_tooltip_trigger(result_container)

              result_container.find(".chart").easyPieChart({
                easing: "easeOutBounce",
                barColor: bpm_chart_barcolor,
                trackColor: bpm_chart_trackcolor,
                scaleColor: bpm_chart_scalecolor,
                lineCap: bpm_chart_linecap,
                lineWidth: bpm_chart_linewidth,
                animate: bpm_chart_animate_time,
                onStep: function (from, to, percent) {
                  jQuery(this.el).find(".percent").text(Math.round(percent))
                },
              })
            })
        } else {
          result_container.html(bpml_poll_result_text)
          bpm_animate_bar()
        }
      } else {
        alert("Opss! Something is going wrong :(")
      }
    })
  }

  function bpm_tooltip_trigger(result_container) {
    result_container.find(".chart").each(function (index) {
      result_container.find(".chart:even").tooltipster({ position: "top", timer: 5000, offsetX: -5 })
      result_container.find(".chart:odd").tooltipster({ position: "bottom", timer: 5000, offsetX: -5 })

      var tipsy_content = jQuery(this)

      jQuery(this)
        .delay(500 * index)
        .fadeIn(1000, function () {
          tipsy_content.tooltipster()
          tipsy_content.tooltipster("show")
        })
    })
  }

  /*--- Attach Tooltip on Share bUtton ---*/

  if (jQuery(".btn-share").length > 0) {
    setTimeout(function () {
      jQuery(".btn-share").tooltipster({ fade: true, gravity: "n" })
    }, 100)

    // Custom Share Link
    // @since: version 1.1.2

    jQuery(".bpm_share").click(function () {
      var bpm_share_btn = window.open(jQuery(this).prop("href"), "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600")
      if (window.focus) {
        bpm_share_btn.focus()
      }
      return false
    })
  }

  /*--- Poll Multiple Vote Section  ---*/

  if (jQuery(".btn-poll-vote-now").length) {
    jQuery(".btn-poll-vote-now").each(function () {
      var btn_poll_vote_now = jQuery(this)
      var poll_container_id = jQuery(this).attr("data-poll-container-id")

      btn_poll_vote_now.removeAttr("disabled")

      bpm_question_container = jQuery("#bpm-question-" + poll_container_id)
      result_container = jQuery("#bpm-result-" + poll_container_id)
      action_container = jQuery("#action-container-" + poll_container_id)

      // Get total no of poll options.

      var bpm_poll_options_container = bpm_question_container.find(".bpm-poll-options"),
        bpm_btn_poll_vote_now = action_container.find(".btn-poll-vote-now"),
        bpm_count_submitted_answer

      bpm_poll_options_container.find("input[type=checkbox]").on("click", function () {
        bpm_total_poll_options = bpm_poll_options_container.attr("data-poll-total-options")
        //                console.log(bpm_total_poll_options);

        // Get maxiumum no of answer submission value.
        bpm_max_answer = bpm_btn_poll_vote_now.attr("data-max-answer")
        //                console.log(bpm_max_answer);

        // Now we are going to count how many checkbox been checked by user.

        bpm_count_submitted_answer = bpm_poll_options_container.find("input[type=checkbox]:checked").length
        //                console.log(bpm_count_submitted_answer);

        if (bpm_count_submitted_answer > bpm_max_answer) {
          var bpm_options_notification_text = bpm_max_answer > 1 ? bpm_text_options : bpm_text_option

          jQuery.toast({
            text: bpm_text_max_answer + " " + bpm_max_answer + " " + bpm_options_notification_text,
            heading: bpm_text_notification,
            position: "bottom-left",
            bgColor: bpm_notification_bgcolor,
            textColor: bpm_notification_textcolor,
            hideAfter: 2500,
            stack: 1,
          })
          return false
        }
      })

      // Button Click Action

      btn_poll_vote_now.on("click", function () {
        // Again get no of checked box been checked, If return value is zero then we are going to show a message.
        var btn_poll_vote = jQuery(this)
        bpm_count_submitted_answer = bpm_poll_options_container.find("input[type=checkbox]:checked").length

        if (bpm_count_submitted_answer == 0) {
          jQuery.toast({
            text: bpm_text_choose_one_option,
            heading: bpm_text_notification,
            position: "bottom-left",
            bgColor: bpm_notification_bgcolor,
            textColor: bpm_notification_textcolor,
            hideAfter: 2500,
            stack: 1,
          })

          return false
        }

        // Get poll id value.

        var poll_id = jQuery(this).data("poll-id"),
          poll_bar_theme = jQuery(this).data("theme"),
          poll_animate_class = jQuery(this).data("animate-class")

        // Now we are going to get value from checked checkbox.

        var choose_id = []

        var bpm_array_index = 0

        bpm_poll_options_container.find("input[type=checkbox]:checked").each(function () {
          choose_id[bpm_array_index] = jQuery(this).val()
          bpm_array_index++
        })

        /*--- If everything is fine, we are going to submit values ---*/

        var bpm_poll_question_style = bpm_question_container.find(".bpm-poll-question").attr("style")

        var bpm_question_container_height = bpm_question_container.find(".bpm-poll-options").outerHeight()

        bpm_question_container.fadeOut("slow", function () {
          btn_poll_vote.attr("disabled", "disabled") // Disable Vote Now button.

          result_container.html('<p class="bpm-poll-results-title" style="' + bpm_poll_question_style + '">' + bpm_text_poll_result + '</p><p class="bpm-wait-message-container"><img src="' + bpm_load_icon_url + '" alt="" /></p>')
          result_container.find(".bpm-wait-message-container").attr("style", " height : " + bpm_question_container_height + "px; line-height: " + bpm_question_container_height + "px;")

          result_container.fadeIn("slow", function () {
            pm_count_vote(poll_id, choose_id, poll_bar_theme, poll_animate_class)
            // change button status.
            action_container.find(".btn-poll-flip").data("status", "1").text(bpm_text_show_voting_panel)
            bpm_question_container.find('input[name="poll-option"]').prop("checked", false)
          })
        })
      })
    })
  }
})
