<?php if($paginator->hasPages()): ?>
    <ul class="pager pagNav ml-auto float-right row">
        
        <?php if($paginator->onFirstPage()): ?>
            <li class="disabled link"><span>Previous</span></li>
        <?php else: ?>
        <li class="link"><a href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev">Previous</a></li>
        <?php endif; ?>
        
        <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
            <?php if(is_string($element)): ?>
                <li class="disabled link"><span><?php echo e($element); ?></span></li>
            <?php endif; ?>
            
            <?php if(is_array($element)): ?>
                <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($page == $paginator->currentPage()): ?>
                        <li class="active my-active link2"><span><?php echo e($page); ?></span></li>
                    <?php else: ?>
                    <li class="link2"><a href="<?php echo e($url); ?>"><span><?php echo e($page); ?></span></a></li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        
        <?php if($paginator->hasMorePages()): ?>
        <li class="link"><a href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next">Next</a></li>
        <?php else: ?>
            <li class="disabled link"><span>Next</span></li>
        <?php endif; ?>
    </ul>
<?php endif; ?>