@extends('layouts.app')
@section('title','Conversations')

@section('content')
    @parent
    @if (!$conversations->isEmpty())
        <conversations-page inline-template
                            :conversations="{{$conversations}}"
                            :current-user="{{$user->id}}">
            <div class="container">
                <div class="row">
                    <div class="col-3 padding-0">
                        <ul class="list-group">
                            <conversation-button v-for="conversation in conversations" :key="conversation.id"
                                                 :conversation="conversation"
                                                 :current-user="currentUser"
                                                 v-on:conversation-change="changeConversation"
                                                 :current="currentConversation">
                            </conversation-button>
                        </ul>
                    </div>
                    <div class="col-8 padding-0">
                        <conversation btn-txt="@lang('conversations/conversation.button')" :current-user="currentUser"
                                      :sender="sender" :conversation="currentConversation">
                        </conversation>
                    </div>
                </div>
            </div>
        </conversations-page>
    @else
        <div class="container">
            <div class="alert alert-info" role="alert">
                <h3>@lang('conversations/conversation.noConversations')</h3>
            </div>
        </div>
    @endif
@endsection