$(document).ready () ->
    overlay = $(".overlay")
    lockOverlay = $(".lockOverlay")
    countDown = $(".countDown")
    unlockInput = lockOverlay.find("input.unlock")
    unlockBtn = lockOverlay.find("button.unlock")
    chatContainer = $(".chatContainer")
    inputContainer = $(".inputContainer")
    sendOnEnterCheckbox = $("#sendOnEnter")

    lockBtn = $(".lock")
    sendBtn = $(".btn.send")
    updateAllBtn = $(".btn.updateAll")
    logoutBtn = $(".btn.logout")

    # seconds till locking happens
    timeout = 60

    lockTimer = null
    lockInterval = null
    sendOnEnter = true

    clearTimers = () ->
        if lockTimer?
            clearTimeout lockTimer
        if lockInterval?
            clearInterval lockInterval
        countDown.text ""
        return true


    lockScreen = () ->
        clearTimers()

        lockOverlay.fadeIn(40)
        unlockInput.focus()
        return true

    updateTimeInLock = (evt = {}) ->
        clearTimers()

        msecs = timeout * 1000
        lockTimer = setTimeout lockScreen, msecs
        lockInterval = setInterval(
            () ->
                if msecs > 0
                    countDown.text(msecs / 1000)
                    msecs -= 1000
                else
                    countDown.text ""
                    clearInterval lockInterval
                return true
            1000
        )

        if evt.which is 13 and sendOnEnter
            sendBtn.click()
            return false

        return true

    unlockScreen = () ->
        $.post "php/api.php?r=unlock", {pw: unlockInput.val()}, (resp) ->
            if resp is "true"
                lockOverlay.fadeOut(100)
                unlockInput.removeClass("error").val("")
                updateTimeInLock()
            else
                unlockInput.addClass("error")
            return true
        return true

    inputContainer.find("#message").keyup (evt) ->
        return updateTimeInLock(evt)

    sendOnEnterCheckbox.change () ->
        sendOnEnter = not sendOnEnter
        return true


    lockBtn.click () ->
        lockScreen()
        return true


    unlockInput.keyup (evt) ->
        if evt.which is 13
            return unlockScreen()
        return true
    unlockBtn.click () ->
        return unlockScreen(resp)


    sendBtn.click () ->
        textarea = $("#message")
        content = textarea.val()

        # prevent empty message sending
        if content.length is 0
            textarea[0].focus()
            return true

        $.post "php/api.php?r=save", {n: $("#name").val(), m: content}, () ->
            # empty input
            $(".message").val("")
            # break update cycle and start new
            clearTimeout nextTimeout
            update()
            return @

        textarea[0].focus()

        return true


    updateAllBtn.click () ->
        # show ajax loader
        overlay.fadeIn 100, () ->
            $.post "php/api.php?r=update_all", () ->
                # break update cycle
                clearTimeout nextTimeout
                chatContainer.empty()
                window.latestTimestamp = null
                update("update_all")

                # hide ajax loader
                # 600 is arbitrary...for the browser to actually be done
                overlay.delay(600).fadeOut(100)

                updateTimeInLock()

                return @
            return true
        return true


    logoutBtn.click () ->
        $.post "php/api.php?r=logout", () ->
            # break update cycle
            clearTimeout nextTimeout
            open "index.php", "_self"
            return @
        return true


    window.latestTimestamp = null

    updateTimeInLock()
    update()

    adjustContainerSize = () ->
        # 60 = 40 (margin-bottom) + 20 (padding)
        chatContainer.css "height", window.innerHeight - inputContainer.outerHeight() - 60
        return true

    $(window).resize () ->
        return adjustContainerSize()

    adjustContainerSize()

    return true


hexToRgb = (hex) ->
    # Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
    shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i
    hex = hex.replace shorthandRegex, (m, r, g, b) ->
        return r + r + g + g + b + b

    result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex)
    return {
        r: parseInt(result[1], 16)
        g: parseInt(result[2], 16)
        b: parseInt(result[3], 16)
    }

fgFromBg = (hex) ->
    rgb = hexToRgb hex

    # lightness = Math.round(((parseInt(rgb[0], 10) * 299) + (parseInt(rgb[1], 10) * 587) + (parseInt(rgb[2], 10) * 114)) / 1000)
    lightness = Math.round( ((rgb.r * 299) + (rgb.g * 587) + (rgb.b * 114)) / 1000 )

    if lightness > 125
        return "black"

    return "white"


showMessages = (messages) ->
    container = $ ".chatContainer"

    updated = $ """<div class="row updated" />"""

    for message in messages
        alignment = if message.name is $("#name").val() then "right" else "left"

        date = moment(message.timestamp, "X")

        # background coloring
        if not date.isBefore(new Date(), "day")
            bg = "#" + string_to_color(message.name)
        else
            bg = "#cccccc"

        textColor = fgFromBg bg

        # link detection
        words = message.message.split " "
        newMessage = []
        for word, idx in words
            # first chars of word are "http"
            if word.slice(0, 5) is "https"
                newMessage.push "<a style='color: #{textColor};' href='#{word}' target='_blank'>#{word.slice(8)}</a>"
            else if word.slice(0, 4) is "http"
                newMessage.push "<a style='color: #{textColor};' href='#{word}' target='_blank'>#{word.slice(7)}</a>"
            else
                newMessage.push word
        newMessage = newMessage.join " "


        # updated.append """<div class="message #{alignment}" style="background-color: #{bg}; color: #{textColor};">
        updated.append """<div class="col-xs-9 col-md-8 col-lg-7 message #{if alignment is "left" then "" else "col-xs-push-3 col-md-push-4 col-lg-push-5"}" style="background-color: #{bg}; color: #{textColor};">
                                <div class="name">#{message.name}</div>
                                <div class="time">#{date.format("HH:mm")}</div>
                                <div class="content">#{newMessage}</div>
                            </div>
                            <div class="clear" />"""


    # append everything to DOM
    container.append updated

    # make emoticons (after being appended because it takes a while)
    updated
        .find ".content"
        .emoticonize()

    updated.slideDown 500, () ->
        # scroll to bottom
        div = container[0]
        div.scrollTop = div.scrollHeight

        return true


    return @

# counter used for user api call (only every nth update the users will be updated)
__updateCounter = -1
# indicates whether the placeholder message is currently shown or not
placeholderMessage = true

# update chat log
update = (action = "update") ->
    $.post "php/api.php?r=#{action}", {ts: latestTimestamp}, (response) ->
        response = JSON.parse response

        if response.length is 0
            # no response and first update => show placeholder message
            if not latestTimestamp?
                showMessages [{
                    name: "System"
                    message: "...heute noch keine Nachrichten..."
                    timestamp: -1
                }]
                # $(".name:contains('System')").parent().css "margin-left", 200
                window.latestTimestamp = -1
                return true
            return false

        if placeholderMessage
            $(".name:contains('System')").parent().remove()
            place = false

        response = response.sort (a, b) ->
            return a.timestamp - b.timestamp

        # update lastest timestamp
        window.latestTimestamp = response[response.length - 1].timestamp

        showMessages(response)

        # console.log response
        return true

    if ++__updateCounter % 3 is 0
        $.post "php/api.php?r=users", (response) ->
            users = JSON.parse response
            # console.log "users", users
            usersDiv = $(".inputContainer .users")
            usersDiv.empty().html "Angemeldete Nutzer: "

            for user in users
                bg = "#" + string_to_color(user)
                usersDiv.append "<span class='label' style='background-color: #{bg}; color: #{fgFromBg(bg)};'>#{user}</span>"

            return true

    # check again later
    window.nextTimeout = setTimeout update, 10000

    return @
