<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <?php echo e(__('تفاصيل العرض')); ?> #<?php echo e($offre->id); ?>

            </h2>
            <a href="<?php echo e(route('craftsman.offres.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <?php echo e(__('العودة إلى قائمة العروض')); ?>

            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100"><?php echo e(__('معلومات العرض')); ?></h3>
                        
                        <!-- حالة العرض -->
                        <div>
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                <?php if($offre->status == 'pending'): ?> bg-yellow-100 text-yellow-800 <?php endif; ?>
                                <?php if($offre->status == 'accepted'): ?> bg-green-100 text-green-800 <?php endif; ?>
                                <?php if($offre->status == 'rejected'): ?> bg-red-100 text-red-800 <?php endif; ?>
                            ">
                                <?php if($offre->status == 'pending'): ?>
                                    <?php echo e(__('قيد الانتظار')); ?>

                                <?php elseif($offre->status == 'accepted'): ?>
                                    <?php echo e(__('مقبول')); ?>

                                <?php elseif($offre->status == 'rejected'): ?>
                                    <?php echo e(__('مرفوض')); ?>

                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4"><?php echo e(__('معلومات أساسية')); ?></h4>
                                
                                <div class="mb-3">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('تاريخ تقديم العرض')); ?></span>
                                    <span class="block mt-1 text-gray-800 dark:text-gray-200"><?php echo e($offre->created_at->format('Y-m-d H:i')); ?></span>
                                </div>
                                
                                <div class="mb-3">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('المشروع')); ?></span>
                                    <span class="block mt-1 text-gray-800 dark:text-gray-200">
                                        <a href="<?php echo e(route('commandes.show', $offre->commande_id)); ?>" class="text-blue-600 hover:underline dark:text-blue-400">
                                            <?php echo e($offre->commande->titre); ?>

                                        </a>
                                    </span>
                                </div>
                                
                                <div class="mb-3">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('السعر المقترح')); ?></span>
                                    <span class="block mt-1 text-gray-800 dark:text-gray-200"><?php echo e($offre->price); ?> ريال</span>
                                </div>
                                
                                <div class="mb-3">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('تاريخ التسليم المتوقع')); ?></span>
                                    <span class="block mt-1 text-gray-800 dark:text-gray-200"><?php echo e($offre->delivery_date->format('Y-m-d')); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4"><?php echo e(__('وصف طريقة العمل')); ?></h4>
                                <div class="text-gray-800 dark:text-gray-200 whitespace-pre-line"><?php echo e($offre->description); ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if(isset($offre->media) && count($offre->media) > 0): ?>
                    <div class="mt-6">
                        <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4"><?php echo e(__('الوسائط المرفقة')); ?></h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <?php $__currentLoopData = $offre->media; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="rounded-lg overflow-hidden shadow">
                                    <?php if(isset($media['type']) && str_contains($media['type'], 'image')): ?>
                                        <a href="<?php echo e(isset($media['path']) ? asset('storage/' . $media['path']) : $media['url']); ?>" target="_blank">
                                            <img src="<?php echo e(isset($media['path']) ? asset('storage/' . $media['path']) : $media['url']); ?>" 
                                                alt="<?php echo e($media['name'] ?? 'صورة العرض'); ?>" 
                                                class="h-40 w-full object-cover">
                                        </a>
                                    <?php elseif(isset($media['type']) && str_contains($media['type'], 'video')): ?>
                                        <video class="h-40 w-full object-cover" controls>
                                            <source src="<?php echo e(isset($media['path']) ? asset('storage/' . $media['path']) : $media['url']); ?>" type="<?php echo e($media['type']); ?>">
                                            <?php echo e(__('متصفحك لا يدعم عرض الفيديو')); ?>

                                        </video>
                                    <?php elseif(isset($media['type']) && $media['type'] == 'url'): ?>
                                        <div class="h-40 p-4 flex items-center justify-center bg-gray-100 dark:bg-gray-800">
                                            <a href="<?php echo e($media['url']); ?>" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline break-all">
                                                <?php echo e($media['url']); ?>

                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- خيارات -->
                    <?php if($offre->status == 'pending'): ?>
                    <div class="mt-6 flex justify-end">
                        <a href="<?php echo e(route('offres.edit', $offre)); ?>" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition ease-in-out duration-150 ml-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 -mt-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            <?php echo e(__('تعديل العرض')); ?>

                        </a>
                        
                        <form action="<?php echo e(route('offres.destroy', $offre)); ?>" method="POST" class="inline-block">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition ease-in-out duration-150" onclick="return confirm('<?php echo e(__('هل أنت متأكد من حذف هذا العرض؟')); ?>');">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 -mt-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <?php echo e(__('حذف العرض')); ?>

                            </button>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?> <?php /**PATH C:\laragon\www\hirafi\resources\views/craftsman/offres/show.blade.php ENDPATH**/ ?>