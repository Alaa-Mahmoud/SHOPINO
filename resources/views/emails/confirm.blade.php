Hello {{$user->name}}
You changed yor email , so we need to verify your new email , Please use the link below:
{{route('verify',$user->verification_token)}}