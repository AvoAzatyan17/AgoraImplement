<?php

namespace App\Services;

use RtcTokenBuilder2;

class AgoraService
{
    public function generateToken(string $channelName, int $uid = 0, int $expireTimeInSeconds = 3600): string
    {
        $appId = config('agora.app_id');
        $appCertificate = config('agora.app_certificate');
        $role = RtcTokenBuilder2::ROLE_PUBLISHER;
        $expireTimestamp = now()->timestamp + $expireTimeInSeconds;
        return RtcTokenBuilder2::buildTokenWithUid(
            $appId,
            $appCertificate,
            $channelName,
            $uid,
            $role,
            $expireTimestamp
        );
    }
}
