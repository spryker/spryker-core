import { HttpClient } from '@angular/common/http';
import {
    ChangeDetectionStrategy,
    ChangeDetectorRef,
    Component,
    ElementRef,
    HostListener,
    Injector,
    Input,
    OnDestroy,
    OnInit,
    ViewChild,
    ViewEncapsulation,
    ContentChild,
    AfterContentInit,
} from '@angular/core';
import { AjaxActionService } from '@spryker/ajax-action';
import { ConfirmModalData, ModalService } from '@spryker/modal';
import { ToJson } from '@spryker/utils';
import { Subject, catchError, defer, of, shareReplay, switchMap, takeUntil, tap } from 'rxjs';

@Component({
    selector: 'mp-form-submitter',
    templateUrl: './form-submitter.component.html',
    styleUrls: ['./form-submitter.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: { class: 'mp-form-submitter', '[class.mp-form-submitter--loading]': 'isLoading' },
})
export class FormSubmitterComponent implements OnInit, OnDestroy, AfterContentInit {
    constructor(
        private modalService: ModalService,
        private ajaxActionService: AjaxActionService,
        private injector: Injector,
        private http: HttpClient,
        private cdr: ChangeDetectorRef,
    ) {}

    @Input() action: string;
    @Input() method = 'POST';
    @Input() @ToJson() confirmation?: ConfirmModalData;
    @Input() buttonMode = false;

    @ViewChild('form') form: ElementRef<HTMLFormElement>;
    @ContentChild('[button-content]') buttonContent: ElementRef;

    buttonContentProvided = false;
    private destroyed$ = new Subject<void>();
    private submit$ = new Subject<void>();
    isLoading = false;

    private request$ = defer(() => {
        this.isLoading = true;
        this.cdr.markForCheck();

        return this.http.request(this.method, this.action, {
            body: new FormData(this.form.nativeElement),
        });
    }).pipe(
        catchError((error) => {
            this.isLoading = false;
            this.cdr.markForCheck();
            throw error;
        }),
        tap((response) => {
            this.isLoading = false;
            this.cdr.markForCheck();
            this.ajaxActionService.handle(response, this.injector);
        }),
        shareReplay({ bufferSize: 1, refCount: true }),
    );

    private modal$ = defer(() =>
        this.modalService
            .openConfirm(this.confirmation)
            .afterDismissed()
            .pipe(switchMap((isDiscard) => (isDiscard ? this.request$ : of(null)))),
    );

    private action$ = this.submit$.pipe(switchMap(() => (this.confirmation ? this.modal$ : this.request$)));

    ngOnDestroy(): void {
        this.destroyed$.next();
        this.destroyed$.complete();
    }

    ngOnInit(): void {
        this.action$.pipe(takeUntil(this.destroyed$)).subscribe();
    }

    ngAfterContentInit(): void {
        this.buttonContentProvided = !!this.buttonContent;
    }

    @HostListener('click', ['$event'])
    onClick() {
        if (this.buttonMode) {
            return;
        }

        if (!this.confirmation) {
            this.isLoading = true;
        }

        this.submit$.next();
    }

    onButtonClick(event: Event) {
        event.stopPropagation();
        event.preventDefault();

        if (!this.form || !this.form.nativeElement) {
            console.error('Form element is not accessible');
            return;
        }

        if (!this.confirmation) {
            this.isLoading = true;
        }

        this.submit$.next();
    }
}
