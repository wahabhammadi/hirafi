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
                <?php echo e(__('تقييماتي')); ?>

            </h2>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- ملخص التقييمات -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="text-center md:text-right mb-4 md:mb-0">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2"><?php echo e(__('ملخص التقييمات')); ?></h3>
                        <p class="text-gray-600 dark:text-gray-400"><?php echo e(__('هذه هي التقييمات التي حصلت عليها من العملاء بعد إكمال المشاريع.')); ?></p>
                    </div>
                    <div class="text-center">
                        <div class="flex items-center justify-center mb-2">
                            <span class="text-3xl font-bold text-gray-800 dark:text-gray-200 ml-2"><?php echo e(number_format($averageRating, 1)); ?></span>
                            <div class="flex">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <?php if($i <= round($averageRating)): ?>
                                        <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    <?php else: ?>
                                        <svg class="w-6 h-6 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('بناءً على')); ?> <?php echo e($ratingsCount); ?> <?php echo e(__('تقييم')); ?></p>
                    </div>
                </div>
            </div>

            <!-- قائمة التقييمات -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4"><?php echo e(__('جميع التقييمات')); ?></h3>
                    
                    <?php if($ratedOffres->count() > 0): ?>
                        <div class="space-y-6">
                            <?php $__currentLoopData = $ratedOffres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $offre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-0 last:pb-0">
                                    <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                                        <div class="mb-4 md:mb-0">
                                            <div class="flex items-center mb-2">
                                                <div class="flex">
                                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                                        <?php if($i <= $offre->rating): ?>
                                                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                            </svg>
                                                        <?php else: ?>
                                                            <svg class="w-5 h-5 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                            </svg>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                </div>
                                                <span class="text-sm text-gray-600 dark:text-gray-400 mr-2"><?php echo e($offre->rating); ?>/5</span>
                                            </div>
                                            <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-2"><?php echo e($offre->commande->titre); ?></h4>
                                            <?php if($offre->review): ?>
                                                <p class="text-gray-700 dark:text-gray-300 mb-2 whitespace-pre-line"><?php echo e($offre->review); ?></p>
                                            <?php else: ?>
                                                <p class="text-gray-500 dark:text-gray-400 italic"><?php echo e(__('لم يترك العميل تعليقًا.')); ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 md:text-left">
                                            <p><?php echo e(__('رقم المشروع')); ?>: <?php echo e($offre->commande_id); ?></p>
                                            <p><?php echo e(__('تاريخ التقييم')); ?>: <?php echo e($offre->updated_at->format('Y-m-d')); ?></p>
                                            <p><?php echo e(__('سعر العرض')); ?>: <?php echo e($offre->price); ?> <?php echo e(__('ريال')); ?></p>
                                            <a href="<?php echo e(route('craftsman.offres.show', $offre)); ?>" class="text-blue-600 dark:text-blue-400 hover:underline mt-2 inline-block">
                                                <?php echo e(__('عرض تفاصيل العرض')); ?>

                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        
                        <div class="mt-6">
                            <?php echo e($ratedOffres->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-gray-700 dark:text-gray-300"><?php echo e(__('لا توجد تقييمات بعد')); ?></h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400"><?php echo e(__('عندما يقوم العملاء بتقييم عملك بعد إكمال المشاريع، ستظهر التقييمات هنا.')); ?></p>
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
<?php endif; ?> <?php /**PATH C:\laragon\www\hirafi\resources\views/craftsman/ratings.blade.php ENDPATH**/ ?>