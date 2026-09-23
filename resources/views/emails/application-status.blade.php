<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $status === 'accepted' ? 'Application Accepted' : 'Application Update' }}</title>
</head>

<body style="margin:0;padding:0;background:#f4f5f7;font-family:Arial,sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:24px 0;">
        <tr>
            <td align="center">

                <table role="presentation"
                       width="600"
                       cellpadding="0"
                       cellspacing="0"
                       style="max-width:600px;width:100%;background:#ffffff;border-radius:8px;overflow:hidden;">

                    {{-- Header --}}
                    <tr>
                        <td style="background:#0d6efd;padding:24px 32px;">
                            <h1 style="margin:0;color:#ffffff;font-size:20px;">
                                {{ $status === 'accepted' ? 'Application Accepted' : 'Application Update' }}
                            </h1>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:32px;">

                            <p style="margin:0 0 16px;color:#212529;font-size:16px;">
                                مرحباً، {{ $volunteerName }}
                            </p>

                            <p style="margin:0 0 16px;color:#495057;font-size:15px;line-height:1.6;">
                                @if($status === 'accepted')

                                    Your application for the opportunity
                                    <strong>{{ $jobTitle }}</strong>
                                    has been accepted by
                                    <strong>{{ $organizationName }}</strong>.

                                @else

                                    Your application for the opportunity
                                    <strong>{{ $jobTitle }}</strong>
                                    has been rejected by
                                    <strong>{{ $organizationName }}</strong>.

                                @endif
                            </p>

                            <table role="presentation"
                                   width="100%"
                                   cellpadding="12"
                                   cellspacing="0"
                                   style="background:#f8f9fa;border-radius:6px;margin:20px 0;">

                                <tr>
                                    <td style="color:#6c757d;font-size:13px;width:70%;">
                                        Volunteer Name
                                    </td>
                                    <td style="color:#212529;font-size:14px;font-weight:600;">
                                        {{ $volunteerName }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="color:#6c757d;font-size:13px;">
                                        Opportunity
                                    </td>
                                    <td style="color:#212529;font-size:14px;font-weight:600;">
                                        {{ $jobTitle }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="color:#6c757d;font-size:13px;">
                                        Organization
                                    </td>
                                    <td style="color:#212529;font-size:14px;font-weight:600;">
                                        {{ $organizationName }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="color:#6c757d;font-size:13px;">
                                        Application Status
                                    </td>
                                    <td style="color:#212529;font-size:14px;font-weight:600;">
                                        {{ ucfirst($status) }}
                                    </td>
                                </tr>

                            </table>

                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background:#f8f9fa;padding:16px 32px;text-align:center;">
                            <p style="margin:0;color:#6c757d;font-size:12px;">
                                Volunteer Platform
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>