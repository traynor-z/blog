<!DOCTYPE html>
<html>
  
  <head>
    @include('admin.public.style')
    @include('admin.public.script')
    <meta charset="UTF-8">
    <title>角色授权</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />

    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
      <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
      <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  
  <body>
    <div class="x-body">
        <form class="layui-form" action="">
          <div class="layui-form-item">
            <label for="L_user_name" class="layui-form-label">
                <span class="x-red">*</span>用户名称
            </label>
            <div class="layui-input-inline">
                <input type="text" id="L_user_name" name="user_name" required="" lay-verify=""
                autocomplete="off" class="layui-input" value="{{$name}}">
                <input type="hidden" name="user_id" value="{{$id}}">
          </div>
          <div class="layui-form-item">
            <label class="layui-form-label">角色列表</label>
            <div class="layui-input-block">
              @foreach ($roles as $v)  
                <input type="checkbox" name="role_id[]" title="{{$v->name}}" value="{{$v->id}}"
                  @if (in_array($v->id,$been_roles))
                      checked  
                  @endif
                >
              @endforeach
              {{-- <input type="checkbox" name="like[write]" title="写作" checked>
              <input type="checkbox" name="like[dai]" title="发呆"> --}}
            </div>
          </div>
          <div class="layui-form-item">
              <label for="L_repass" class="layui-form-label">
              </label>
              <button  class="layui-btn" lay-filter="edit" lay-submit="">
                  授权
              </button>
          </div>
      </form>
    </div>
    <script>
      layui.use(['form','layer'], function(){
          $ = layui.jquery;
        var form = layui.form
        ,layer = layui.layer;
      
        //自定义验证规则
        form.verify({
          nikename: function(value){
            if(value.length < 5){
              return '昵称至少得5个字符啊';
            }
          }
          ,pass: [/(.+){6,12}$/, '密码必须6到12位']
          ,repass: function(value){
              if($('#L_pass').val()!=$('#L_repass').val()){
                  return '两次密码不一致';
              }
          }
        });

        //监听提交
        form.on('submit(edit)', function(data){

          $.ajax({
                type:'post',
                url:"/admin/user/doAuth",
                dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:data.field,
                success:function(data){
                    //成功提示，并刷新页面                   
                    if(data.status==0){

                        //发异步，把数据提交给php
                        layer.alert("增加成功", {icon: 6},function () {
                            // 获得frame索引
                            var index = parent.layer.getFrameIndex(window.name);
                            //关闭当前frame
                            parent.layer.close(index);
                        });

                    }else{
                        layer.alert(data.message,{icon:5});
                    }

                },error:function(){
                    //错误信息
                }
            });


          // console.log(data);
          
          return false;
        });
        
        
      });
  </script>
    <script>var _hmt = _hmt || []; (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
      })();</script>
  </body>

</html>