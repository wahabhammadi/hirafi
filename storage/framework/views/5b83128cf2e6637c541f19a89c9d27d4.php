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
                <?php echo e(__('تفاصيل المشروع')); ?>

            </h2>
            <div class="flex space-x-2 rtl:space-x-reverse">
                <a href="<?php echo e(route('commandes.edit', $commande)); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <?php echo e(__('تعديل المشروع')); ?>

                </a>
                <a href="<?php echo e(route('commandes.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <?php echo e(__('العودة إلى المشاريع')); ?>

                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <?php if(session('success')): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p><?php echo e(session('success')); ?></p>
                </div>
            <?php endif; ?>

            <!-- بطاقة تفاصيل المشروع -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-2"><?php echo e($commande->titre); ?></h1>
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            <?php if($commande->statue == 'pending'): ?> bg-yellow-100 text-yellow-800 <?php endif; ?>
                            <?php if($commande->statue == 'in_progress'): ?> bg-blue-100 text-blue-800 <?php endif; ?>
                            <?php if($commande->statue == 'completed'): ?> bg-green-100 text-green-800 <?php endif; ?>
                            <?php if($commande->statue == 'rejected'): ?> bg-red-100 text-red-800 <?php endif; ?>
                        ">
                            <?php if($commande->statue == 'pending'): ?>
                                <?php echo e(__('قيد الانتظار')); ?>

                            <?php elseif($commande->statue == 'in_progress'): ?>
                                <?php echo e(__('قيد التنفيذ')); ?>

                            <?php elseif($commande->statue == 'completed'): ?>
                                <?php echo e(__('مكتمل')); ?>

                            <?php elseif($commande->statue == 'rejected'): ?>
                                <?php echo e(__('مرفوض')); ?>

                            <?php endif; ?>
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('معلومات المشروع')); ?></h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="mb-4">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('التخصص المطلوب')); ?></span>
                                    <span class="block mt-1 text-gray-800 dark:text-gray-200"><?php echo e($commande->specialist); ?></span>
                                </div>
                                <div class="mb-4">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('الميزانية')); ?></span>
                                    <span class="block mt-1 text-gray-800 dark:text-gray-200"><?php echo e($commande->budget); ?> SAR</span>
                                </div>
                                <div class="mb-4">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('الولاية')); ?></span>
                                    <span class="block mt-1 text-gray-800 dark:text-gray-200"><?php echo e($commande->province_name); ?></span>
                                </div>
                                <div>
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('تاريخ الإنشاء')); ?></span>
                                    <span class="block mt-1 text-gray-800 dark:text-gray-200"><?php echo e($commande->created_at->format('Y-m-d H:i')); ?></span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('وصف المشروع')); ?></h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-gray-800 dark:text-gray-200 whitespace-pre-line"><?php echo e($commande->description); ?></p>
                            </div>
                        </div>
                    </div>

                    <?php if($commande->media && count($commande->media) > 0): ?>
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4"><?php echo e(__('الوسائط والمرفقات')); ?></h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <?php $__currentLoopData = $commande->media; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(isset($media['type']) && str_contains($media['type'], 'image')): ?>
                                    <div class="relative group">
                                        <img src="<?php echo e(isset($media['path']) ? asset('storage/' . $media['path']) : $media['url']); ?>" 
                                            alt="<?php echo e($media['name'] ?? 'صورة المشروع'); ?>" 
                                            class="h-40 w-full object-cover rounded-lg shadow-sm">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-opacity duration-300 rounded-lg flex items-center justify-center">
                                            <a href="<?php echo e(isset($media['path']) ? asset('storage/' . $media['path']) : $media['url']); ?>" 
                                               target="_blank" 
                                               class="text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                <?php elseif(isset($media['type']) && str_contains($media['type'], 'video')): ?>
                                    <div class="relative group">
                                        <video class="h-40 w-full object-cover rounded-lg shadow-sm" controls>
                                            <source src="<?php echo e(isset($media['path']) ? asset('storage/' . $media['path']) : $media['url']); ?>" type="<?php echo e($media['type']); ?>">
                                            <?php echo e(__('متصفحك لا يدعم عرض الفيديو')); ?>

                                        </video>
                                    </div>
                                <?php elseif(isset($media['type']) && $media['type'] == 'url'): ?>
                                    <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                                        <a href="<?php echo e($media['url']); ?>" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline break-all">
                                            <?php echo e($media['url']); ?>

                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="mt-8 flex justify-between">
                        <?php if(auth()->user()->role === 'client' && auth()->id() === $commande->user_id): ?>
                        <form action="<?php echo e(route('commandes.destroy', $commande)); ?>" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من حذف هذا المشروع؟');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition ease-in-out duration-150">
                                <?php echo e(__('حذف المشروع')); ?>

                            </button>
                        </form>
                        <?php elseif(auth()->user()->role === 'craftsman'): ?>
                        <div>
                            <?php
                            $existingOffer = \App\Models\Offre::where('user_id', auth()->id())
                                ->where('commande_id', $commande->id)
                                ->first();
                            ?>
                            
                            <?php if($existingOffer): ?>
                                <a href="<?php echo e(route('offres.edit', $existingOffer)); ?>" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 -mt-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                    <?php echo e(__('تعديل العرض')); ?>

                                </a>
                                
                                <?php if($existingOffer->status == 'pending'): ?>
                                <form action="<?php echo e(route('offres.destroy', $existingOffer)); ?>" method="POST" class="inline-block mr-2" onsubmit="return confirm('هل أنت متأكد من حذف هذا العرض؟');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition ease-in-out duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 -mt-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        <?php echo e(__('حذف العرض')); ?>

                                    </button>
                                </form>
                                <?php endif; ?>
                                
                                <span class="mr-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                    <?php if($existingOffer->status == 'pending'): ?> bg-yellow-100 text-yellow-800 <?php endif; ?>
                                    <?php if($existingOffer->status == 'accepted'): ?> bg-green-100 text-green-800 <?php endif; ?>
                                    <?php if($existingOffer->status == 'rejected'): ?> bg-red-100 text-red-800 <?php endif; ?>
                                ">
                                    <?php if($existingOffer->status == 'pending'): ?>
                                        <?php echo e(__('قيد الانتظار')); ?>

                                    <?php elseif($existingOffer->status == 'accepted'): ?>
                                        <?php echo e(__('مقبول')); ?>

                                    <?php elseif($existingOffer->status == 'rejected'): ?>
                                        <?php echo e(__('مرفوض')); ?>

                                    <?php endif; ?>
                                </span>
                            <?php else: ?>
                                <a href="<?php echo e(route('offres.create', $commande)); ?>" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 -mt-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    <?php echo e(__('تقديم عرض')); ?>

                                </a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
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
<?php endif; ?> <?php /**PATH C:\laragon\www\hirafi\resources\views/commandes/show.blade.php ENDPATH**/ ?>