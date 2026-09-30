<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Member> $members
 */
?>
<div class="members index content">
    <?= $this->Html->link(__('New Member'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Members') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('email') ?></th>
                    <th><?= $this->Paginator->sort('sort_order') ?></th>
                    <th><?= $this->Paginator->sort('deleted_at') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $member): ?>
                <tr>
                    <td><?= $this->Number->format($member->id) ?></td>
                    <td><?= h($member->name) ?></td>
                    <td><?= h($member->email) ?></td>
                    <td><?= $this->Number->format($member->sort_order) ?></td>
                    <td><?= h($member->deleted_at) ?></td>
                    <td><?= h($member->created) ?></td>
                    <td><?= h($member->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $member->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $member->id]) ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $member->id],
                            [
                                'method' => 'delete',
                                'confirm' => __('Are you sure you want to delete # {0}?', $member->id),
                            ]
                        ) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>