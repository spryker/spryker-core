import { HttpClient } from '@angular/common/http';
import { Injectable, Injector } from '@angular/core';
import { UnsavedChangesMonitorToken } from '@spryker/unsaved-changes';
import {
    Observable,
    ReplaySubject,
    Subject,
    catchError,
    map,
    mergeMap,
    of,
    scan,
    shareReplay,
    startWith,
    switchMap,
} from 'rxjs';

export interface Comment {
    message: string;
    createdAt: string;
    fullname: string;
    uuid?: string;
    crf?: string;
    isUpdated?: boolean;
    readonly?: boolean;
}

type Action = 'create' | 'update' | 'remove' | 'error';

interface CommentApi {
    comment: {
        comment_tags: string[];
        created_at: string;
        customer: string;
        is_updated: boolean;
        message: string;
        tag_names: string[];
        updated_at: string;
        uuid: string;
        user: {
            username: string;
            first_name: string;
            last_name: string;
        };
    };
    csrfToken: string;
}

interface CommentPayload {
    form: FormData;
    url: string;
    type: Action;
}

interface AccomplishPayload {
    type: Action;
    id: string;
}

@Injectable({ providedIn: 'root' })
export class CommentsConfiguratorService {
    constructor(private http: HttpClient) {}

    private update$ = new Subject<CommentPayload>();
    private initial$ = new ReplaySubject<Comment[]>(1);
    private httpComments$ = this.update$.pipe(
        mergeMap(({ form, url, type }) =>
            this.http.post<CommentApi | null>(url, form).pipe(
                map(this.commentAdapter),
                map((data) => ({ data, type, id: form.get('uuid') })),
                catchError((error) => {
                    this.error$.next(error.error.messages[0]);
                    return of({ data: null, id: null, type: 'error' });
                }),
            ),
        ),
    );
    private actionsAccomplish$ = new Subject<AccomplishPayload>();
    private error$ = new Subject<string>();
    private comments$ = this.initial$.pipe(
        switchMap((initial) =>
            this.httpComments$.pipe(
                startWith({ data: initial, type: 'initial', id: null }),
                scan((acc, { data, type, id }) => {
                    this.actionsAccomplish$.next({ type: type as Action, id });

                    if (Array.isArray(data)) {
                        return data;
                    }

                    if (type === 'create') {
                        return [...acc, data];
                    }

                    if (type === 'update') {
                        return acc.map((comment) => (comment.uuid === id ? data : comment));
                    }

                    if (type === 'remove') {
                        return acc.filter((comment) => comment.uuid !== id);
                    }

                    return acc;
                }, []),
            ),
        ),
        shareReplay({ refCount: true, bufferSize: 1 }),
    );

    setInitial(comments: Comment[] | string): void {
        this.initial$.next(typeof comments === 'string' ? JSON.parse(comments) : comments);
    }

    getComments(): Observable<Comment[]> {
        return this.comments$;
    }

    commentAction(payload: CommentPayload, injector: Injector): void {
        this.error$.next(null);
        this.update$.next(payload);
        injector.get(UnsavedChangesMonitorToken, null)?.reset();
    }

    getError(): Observable<string> {
        return this.error$.asObservable();
    }

    getAccomplishing(): Observable<AccomplishPayload> {
        return this.actionsAccomplish$.asObservable();
    }

    private commentAdapter(data: CommentApi): Comment {
        if (!data?.comment) {
            return null;
        }

        return {
            message: data.comment.message,
            uuid: data.comment.uuid,
            readonly: false,
            isUpdated: data.comment.is_updated,
            createdAt: data.comment.created_at,
            fullname: `${data.comment.user?.first_name} ${data.comment.user?.last_name}`,
            crf: data.csrfToken,
        };
    }
}
