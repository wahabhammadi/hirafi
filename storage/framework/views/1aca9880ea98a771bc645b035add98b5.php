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
                <?php echo e(__('المشاريع')); ?>

            </h2>
            <a href="<?php echo e(route('commandes.create')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <?php echo e(__('إضافة مشروع جديد')); ?>

            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-center"><?php echo e(__('إجمالي المشاريع')); ?></h3>
                        <p class="text-3xl font-bold text-center mt-2"><?php echo e($totalCommandes); ?></p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-center"><?php echo e(__('قيد الانتظار')); ?></h3>
                        <p class="text-3xl font-bold text-center mt-2 text-yellow-500"><?php echo e($pendingCommandes); ?></p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-center"><?php echo e(__('قيد التنفيذ')); ?></h3>
                        <p class="text-3xl font-bold text-center mt-2 text-blue-500"><?php echo e($inProgressCommandes); ?></p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-center"><?php echo e(__('مكتملة')); ?></h3>
                        <p class="text-3xl font-bold text-center mt-2 text-green-500"><?php echo e($completedCommandes); ?></p>
                    </div>
                </div>
            </div>

            <!-- قائمة المشاريع -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <?php if(session('success')): ?>
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p><?php echo e(session('success')); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <h2 class="text-xl font-semibold mb-6"><?php echo e(__('مشاريعي')); ?></h2>
                    
                    <?php if($commandes->isEmpty()): ?>
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <p><?php echo e(__('لا توجد مشاريع بعد. قم بإضافة مشروع جديد!')); ?></p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <?php echo e(__('عنوان المشروع')); ?>

                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <?php echo e(__('التخصص')); ?>

                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <?php echo e(__('الميزانية')); ?>

                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <?php echo e(__('الحالة')); ?>

                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <?php echo e(__('تاريخ الإنشاء')); ?>

                                        </th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <?php echo e(__('الإجراءات')); ?>

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <?php $__currentLoopData = $commandes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commande): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    <?php echo e($commande->titre); ?>

                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                                    <?php echo e($commande->specialist); ?>

                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                                    <?php echo e($commande->budget); ?> SAR
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
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
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                                    <?php echo e($commande->created_at->format('Y-m-d')); ?>

                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <div class="flex space-x-2 rtl:space-x-reverse justify-center">
                                                    <a href="<?php echo e(route('commandes.show', $commande)); ?>" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                        <?php echo e(__('عرض')); ?>

                                                    </a>
                                                    <a href="<?php echo e(route('commandes.edit', $commande)); ?>" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                        <?php echo e(__('تعديل')); ?>

                                                    </a>
                                                    <form action="<?php echo e(route('commandes.destroy', $commande)); ?>" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من حذف هذا المشروع؟');">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 mr-2">
                                                            <?php echo e(__('حذف')); ?>

                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- التنقل بين الصفحات -->
                        <div class="mt-6">
                            <?php echo e($commandes->links()); ?>

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
<?php endif; ?> <?php /**PATH C:\laragon\www\hirafi\resources\views/commandes/index.blade.php ENDPATH**/ ?>