<select 
    name="<?php echo e($name); ?>" 
    id="<?php echo e($id); ?>" 
    <?php if($required): ?> required <?php endif; ?>
    <?php echo e($attributes->merge(['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-blue-500 dark:focus:ring-blue-500 ' . $class])); ?>

>
    <option value="">-- <?php echo e(__('اختر الولاية')); ?> --</option>
    <?php $__currentLoopData = $provinces(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $province): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($code); ?>" <?php if($selected == $code): echo 'selected'; endif; ?>>
            <?php echo e($province); ?> (<?php echo e($code); ?>)
        </option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</select> <?php /**PATH C:\laragon\www\hirafi\resources\views/components/province-select.blade.php ENDPATH**/ ?>