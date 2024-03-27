import { ChangeDetectionStrategy, Component, Input, OnChanges, SimpleChanges, ViewEncapsulation } from '@angular/core';
import { ToJson } from '@spryker/utils';
import { Comment, CommentsConfiguratorService } from '../../services/comments-configurator';
import { AddComment } from '../add-comment/add-comment.component';
import { CommentActions, CommentTranslations } from '../comment/comment.component';

interface CommentAction {
    label?: string;
    url?: string;
}

type ThreadActions = {
    [P in keyof CommentActions]: CommentAction;
} & { create: CommentAction };

interface Translations {
    updated: string;
}

@Component({
    selector: 'mp-comments-thread',
    templateUrl: './comments-thread.component.html',
    styleUrls: ['./comments-thread.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class CommentsThreadComponent implements OnChanges {
    constructor(private commentsConfigurator: CommentsConfiguratorService) {}

    @Input() @ToJson() comments: Comment[] = [];
    @Input() @ToJson() actions: ThreadActions;
    @Input() @ToJson() add: AddComment;
    @Input() @ToJson() translations: Translations;

    comments$ = this.commentsConfigurator.getComments();
    error$ = this.commentsConfigurator.getError();

    get commentTranslations(): CommentTranslations {
        return Object.entries(this.actions).reduce(
            (acc, [key, value]) => ({ ...acc, [key]: value.label }),
            this.translations as CommentTranslations,
        );
    }

    ngOnChanges(changes: SimpleChanges): void {
        if (changes.comments) {
            this.commentsConfigurator.setInitial(changes.comments.currentValue);
        }
    }
}
