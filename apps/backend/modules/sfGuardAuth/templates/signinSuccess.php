 <div class="container">
    <section>				
        <div id="container_demo" >
            <a class="hiddenanchor" id="toregister"></a>
            <a class="hiddenanchor" id="tologin"></a>
            <div id="wrapper">
                <div id="login" class="animate form">
                    <?php if($form->hasGlobalErrors()): ?>
                        <div id="flash_container">
                            <div id="flash" class="flash flash_notice" style="visibility: visible; opacity: 1; ">
                                <?php echo $form->renderGlobalErrors(); ?>
                                <a href="" id="flash_close">Close</a>
                            </div>
                        </div>
                    <?php endif; ?>
                    <form accept-charset="UTF-8" action="<?php echo url_for('@sf_guard_signin') ?>"  method="post">
                        <h1>Log in</h1> 
                        <div style="margin:0;padding:0;display:inline">
                            <?php echo $form['_csrf_token']->render(); ?>
                        </div>

                        <p> 
                            <label for="username" class="uname" data-icon="u" > <?php echo $form['username']->renderLabel() ?> </label>
                            <?php echo $form['username']->render(array( 'placeholder'=>"myusername or mymail@mail.com",'autocomplete' => 'on', 'id' => 'username', 'size' => 30)); ?>
                            <?php echo $form['username']->renderError(); ?>
                        </p>
                        <p> 
                            <label for="password" class="youpasswd" data-icon="p"> <?php echo $form['password']->renderLabel() ?></label>
                            <?php echo $form['password']->render(array('type'=>"password", 'placeholder'=>"eg. RT6hT!IUY", 'autocomplete' => 'on', 'id' => 'password', 'size' => 30)); ?>
                            <?php echo $form['username']->renderError(); ?>
                        </p>
<!--                        <p class="keeplogin"> 
                                <input type="checkbox" name="loginkeeping" id="loginkeeping" value="loginkeeping" /> 
                                <label for="loginkeeping">Keep me logged in</label>
                        </p>-->
                        <p class="login button"> 
                            <input type="submit" value="Login" /> 
                        </p>
                    </form>
                </div>
            </div>
        </div>  
    </section>
</div>