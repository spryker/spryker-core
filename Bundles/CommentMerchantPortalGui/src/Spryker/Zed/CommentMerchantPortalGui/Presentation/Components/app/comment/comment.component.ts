import {
    ChangeDetectionStrategy,
    Component,
    ElementRef,
    Injector,
    Input,
    ViewChild,
    ViewEncapsulation,
} from '@angular/core';
import { EMPTY, Subject, map, merge, of, startWith, switchMap } from 'rxjs';
import { Comment, CommentsConfiguratorService } from '../../services/comments-configurator';

export interface CommentActions {
    update: unknown;
    edit: unknown;
    remove: unknown;
}

export type CommentTranslations = {
    [P in keyof CommentActions]: string;
} & { updated: string };

@Component({
    selector: 'mp-comment',
    templateUrl: './comment.component.html',
    styleUrls: ['./comment.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-comment',
    },
})
export class CommentComponent {
    constructor(private commentsConfigurator: CommentsConfiguratorService, private injector: Injector) {}

    @Input() comment: Comment = {} as Comment;
    @Input() translations: CommentTranslations;
    @Input() updateUrl: string;
    @Input() removeUrl: string;

    @ViewChild('form') form: ElementRef<HTMLFormElement>;

    editing = false;
    message = this.comment.message ?? '';
    offset = new Date().getTimezoneOffset();

    private loadingTrigger$ = new Subject<void>();

    isLoading$ = merge(
        this.loadingTrigger$.pipe(map(() => true)),
        this.commentsConfigurator.getAccomplishing().pipe(
            switchMap((accomplish) => {
                if (accomplish.id !== this.comment.uuid) {
                    return EMPTY;
                }

                this.editing = false;
                return of(false);
            }),
        ),
    ).pipe(startWith(false));

    valueChange(event: InputEvent): void {
        this.message = (event.target as HTMLInputElement).value;
    }

    useEditingMode(): void {
        this.message = this.comment.message;
        this.editing = true;
    }

    commentAction(type: 'update' | 'remove'): void {
        this.loadingTrigger$.next();

        const data = new FormData(this.form.nativeElement);
        data.append('message', this.message);
        const url = type === 'update' ? this.updateUrl : this.removeUrl;

        this.commentsConfigurator.commentAction({ form: data, url, type }, this.injector);
    }
}
