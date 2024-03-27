import {
    ChangeDetectionStrategy,
    Component,
    ElementRef,
    Injector,
    Input,
    ViewChild,
    ViewEncapsulation,
} from '@angular/core';
import { EMPTY, Subject, map, merge, of, startWith, switchMap, withLatestFrom } from 'rxjs';
import { CommentsConfiguratorService } from '../../services/comments-configurator';

export interface AddComment {
    crf: string;
    ownerId: string;
    ownerType: string;
}

@Component({
    selector: 'mp-add-comment',
    templateUrl: './add-comment.component.html',
    styleUrls: ['./add-comment.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: { class: 'mp-add-comment' },
})
export class AddCommentComponent {
    constructor(private commentsConfigurator: CommentsConfiguratorService, private injector: Injector) {}

    @Input() addComment: AddComment;
    @Input() addUrl: string;

    @ViewChild('form') form: ElementRef<HTMLFormElement>;

    message = '';

    private loadingTrigger$ = new Subject<void>();

    isLoading$ = merge(
        this.loadingTrigger$.pipe(map(() => true)),
        this.commentsConfigurator.getAccomplishing().pipe(
            withLatestFrom(this.commentsConfigurator.getError()),
            switchMap(([accomplish, error]) => {
                if (accomplish.type !== 'create') {
                    return EMPTY;
                }

                if (!error) {
                    this.form?.nativeElement.reset();
                    this.message = '';
                }

                return of(false);
            }),
        ),
    ).pipe(startWith(false));

    valueChange(event: InputEvent): void {
        this.message = (event.target as HTMLInputElement).value;
    }

    addAction(): void {
        this.loadingTrigger$.next();

        const data = new FormData(this.form.nativeElement);
        data.append('message', this.message);

        this.commentsConfigurator.commentAction({ form: data, url: this.addUrl, type: 'create' }, this.injector);
    }
}
