<div class="form-group">
    <label for="widget-name">{{ trans('Base::forms.name') }}</label>
    <input type="text" class="form-control" name="name" value="{{ $config['name'] }}">
</div>
<div class="form-group">
    <label for="content">{{ trans('Base::forms.content') }}</label>
    <textarea name="content" class="form-control" rows="7">{{ $config['content'] }}</textarea>
</div>
