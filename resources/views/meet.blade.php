<!DOCTYPE html>
<html>
<head>
    <title>Create New Meeting</title>
    <script src='https://8x8.vc/external_api.js'></script>
</head>
<body>
    <div id="meet" style="width: 800px; height: 600px;"></div>

    <script>
        var roomName = 'New_Room';
        var userName = 'MG Test';
        var userEmail = 'test@test.com';
        const domain = '8x8.vc';
        const options = {
            roomName: 'vpaas-magic-cookie-d43bd9c418a349d89e1f18f99c826f01/'+roomName,
            width: 320,
            height: 600,
            parentNode: document.querySelector('#meet'),
            userInfo: {
                    email: userEmail,
                    displayName: userName
                },
            configOverwrite: {
            brandingRoomAlias: 'TUV Labs',
            hiddenPremeetingButtons: ['select-background', 'invite', 'settings'],
            startWithAudioMuted: true,
            startWithVideoMuted: true,
            disableModeratorIndicator: false,
            enableAutomaticUrlCopy: false,
            toolbarButtons: ['camera','microphone','whiteboard','chat','desktop',],
            readOnlyName: true,
            prejoinConfig: {
                enabled: true,
            }
            },
            
        };
        const api = new JitsiMeetExternalAPI(domain, options);
    </script>
</body>
</html>
