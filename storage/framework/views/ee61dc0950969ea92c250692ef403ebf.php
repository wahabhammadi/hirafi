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
                <?php echo e(__('ملف الأعمال')); ?>

            </h2>
            <a href="<?php echo e(route('craftsman.portfolio.create')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                <?php echo e(__('إضافة عمل جديد')); ?>

            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <?php if(session('status')): ?>
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded" role="alert">
                    <p><?php echo e(session('status')); ?></p>
                </div>
            <?php endif; ?>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <?php if(count($works) > 0): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php $__currentLoopData = $works; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $work): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="bg-white dark:bg-gray-700 rounded-lg shadow overflow-hidden">
                                    <div class="relative h-48 bg-gray-200 dark:bg-gray-600">
                                        <?php if(isset($work->media) && count($work->media) > 0): ?>
                                            <?php
                                                $firstMedia = collect($work->media)->first();
                                            ?>
                                            
                                            <?php if(isset($firstMedia['type']) && str_contains($firstMedia['type'], 'image')): ?>
                                                <img src="<?php echo e(isset($firstMedia['path']) ? asset('storage/' . $firstMedia['path']) : $firstMedia['url']); ?>" 
                                                     alt="<?php echo e($work->title); ?>" 
                                                     class="w-full h-full object-cover">
                                            <?php elseif(isset($firstMedia['type']) && str_contains($firstMedia['type'], 'video')): ?>
                                                <video class="w-full h-full object-cover" controls>
                                                    <source src="<?php echo e(isset($firstMedia['path']) ? asset('storage/' . $firstMedia['path']) : $firstMedia['url']); ?>" type="<?php echo e($firstMedia['type']); ?>">
                                                </video>
                                            <?php elseif(isset($firstMedia['type']) && $firstMedia['type'] == 'url'): ?>
                                                <div class="w-full h-full flex items-center justify-center bg-gray-100 dark:bg-gray-700 p-4">
                                                    <a href="<?php echo e($firstMedia['url']); ?>" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline truncate">
                                                        <?php echo e($firstMedia['url']); ?>

                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center bg-gray-100 dark:bg-gray-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center bg-gray-100 dark:bg-gray-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        <?php endif; ?>

                                        <?php if($work->is_featured): ?>
                                            <div class="absolute top-2 right-2 bg-yellow-400 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                <?php echo e(__('مميز')); ?>

                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold mb-2"><?php echo e($work->title); ?></h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-2"><?php echo e($work->description); ?></p>
                                        
                                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span><?php echo e(__('تاريخ الإنجاز')); ?>: <?php echo e($work->completion_date->format('Y-m-d')); ?></span>
                                        </div>
                                        
                                        <div class="flex justify-between items-center">
                                            <a href="<?php echo e(route('craftsman.portfolio.show', $work)); ?>" class="text-blue-600 dark:text-blue-400 hover:underline">
                                                <?php echo e(__('عرض التفاصيل')); ?>

                                            </a>
                                            
                                            <div class="flex space-x-2 rtl:space-x-reverse">
                                                <a href="<?php echo e(route('craftsman.portfolio.edit', $work)); ?>" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                </a>
                                                
                                                <form action="<?php echo e(route('craftsman.portfolio.destroy', $work)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('هل أنت متأكد من حذف هذا العمل؟')); ?>');" class="inline">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2"><?php echo e(__('ليس لديك أي أعمال بعد')); ?></h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6"><?php echo e(__('أضف أعمالك لعرضها للعملاء وزيادة فرصك في الحصول على مشاريع')); ?></p>
                            <a href="<?php echo e(route('craftsman.portfolio.create')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-700 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                <?php echo e(__('إضافة عمل جديد')); ?>

                            </a>
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
<?php endif; ?> <?php /**PATH C:\laragon\www\hirafi\resources\views/craftsman/portfolio/index.blade.php ENDPATH**/ ?>