@extends('ifmquiz::front.master')

@section('main-content')
    <div class="front-template">
        <div class="header" data-kui-sticky>
            <div class="container">
                @if ($quiz->header_logo)<img class="header_logo" src="{{ asset($quiz->header_logo) }}" />@endif

                @if ($quiz->time)
                    <span class="time-limit">
                        @if ($quiz->time > 0)
                            <div id="countdown">
                                <img src="{{ asset('img/generic/time.png') }}" width="33" height="33" />
                                <countdown :time="{{ $seconds_remaining * 1000 }}" @countdownend="countdownend">
                                <template slot-scope="props"><span v-show="props.hours > 0">@{{ props.hours }}h</span> @{{ props.minutes }}min</template>
                                </countdown>
                            </div>
                        @endif
                    </span>
                @endif
                <span class="questions-number"><img src="{{ asset('img/generic/questions.png') }}" width="22" height="24" /> {{ sizeof($questions) }} questions</span>

                <h1 class="title">{{ $quiz->title }}</h1>
                <h2 class="subtitle">{{ $quiz->subtitle }}</h2>
            </div>
        </div>

        <div class="container">
            <div class="questions">
                <form action="{{ route('quiz_front_handler', ['uuid' => $quiz->id]) }}" method="post">
                    @foreach($questions as $i => $question)
                        <div class="question @if ($i == 0) first-question @endif @if ($i == sizeof($questions)-1) last-question @endif">
                            <div class="status"></div>
                            <div class="statement">
                                <h3>{{ $question->title }}</h3>
                                <p class="description">{{ $question->description }}</p>
                            </div>

                            <div class="control items">
                                @if ($question->type == 1)
                                    @foreach ($question->items as $j => $item)
                                        <label class="radio">
                                            <input type="radio" name="oneanswer_{{ $question->id }}" value="{{ $item->id }}" /> {{ $item->title }}
                                        </label>
                                    @endforeach
                                @elseif ($question->type == 2)
                                    @foreach ($question->items as $j => $item)
                                        <label class="checkbox">
                                            <input type="checkbox" name="answer_{{ $question->id }}_{{ $item->id }}" /> {{ $item->title }}
                                        </label>
                                    @endforeach
                                @elseif ($question->type == 3)
                                    <div class="columns">
                                        <div class="column left">
                                            @foreach ($question->items_left as $j => $item)
                                                <div class="control">
                                                    <label>
                                                        <span class="n">{{ $j+1 }} - </span> {{ $item->title }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="column right">
                                            @foreach ($question->items_right as $j => $item)
                                                <div class="control">
                                                    <label>
                                                        <input style="padding:0" type="text" name="answer_{{ $question->id }}_{{ $item->id }}" /> {{ $item->title }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @elseif ($question->type == 4)
                                    <textarea class="textarea" placeholder="Répondre içi" name="textanswer_{{ $question->id }}"></textarea>
                                @elseif ($question->type == 5)
                                    <div id="linear-scale-{{ $question->id }}" class="linear-scale" data-min="{{ $question->linear_scale_start_number }}" data-max="{{ $question->linear_scale_end_number }}"></div>
                                    @if ($question->linear_scale_start_label)<span class="linear_scale_start_label">{{ $question->linear_scale_start_label }}</span>@endif
                                    @if ($question->linear_scale_end_label)<span class="linear_scale_end_label">{{ $question->linear_scale_end_label }}</span>@endif
                                    <input type="hidden" name="scaleanswer_{{ $question->id }}" id="linear-scale-{{ $question->id }}-value" />
                                @endif
                            </div>
                        </div>
                    @endforeach

                    {{ csrf_field() }}
                    <input type="hidden" name="attempt_id" value="{{ $attempt_id }}" />

                    <div class="submit">
                        <input type="submit" class="button" value="Valider" />
                    </div>

                </form>

            </div>
        </div>

        <div class="footer">
            <div class="container">
                @if ($quiz->footer_image)<img id="footer_image" class="footer_image" src="{{ asset($quiz->footer_image) }}" width="50%" alt="" />@endif

                @if ($quiz->footer_text)
                    <div class="footer_text">
                        {!! $quiz->footer_text !!}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="{{ asset('js/dist/front.js') }}"></script>
    <script src="{{ asset('js/vendor/sticky.min.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.question .items input, .question .items textarea').change(function(e) {
               $(this).closest('.question').addClass('answered');
            });

            $(".linear-scale").each(function() {
                $(this).slider({
                    value: Math.ceil($(this).data('max') / 2),
                    min: $(this).data('min'),
                    max: $(this).data('max'),
                    step: 1,
                    range: "min",
                    change: function() {
                        $(this).closest('.question').addClass('answered');
                        var input_id = $(this).attr('id') + '-value';
                        console.log(input_id);
                        $('#' + input_id).val($(this).slider('value'));
                    }
                }).each(function() {
                    // Get the options for this slider
                    var opt = $(this).data().uiSlider.options;

                    // Get the number of possible values
                    var vals = opt.max - opt.min;

                    // Space out values
                    for (var i = 0; i <= vals; i++) {
                        var el = $('<label>'+(i+opt.min)+'</label>').css('left', (i/vals*100)+'%');
                        $(this).append(el);
                    }
                });
            });
        });
    </script>

@endsection