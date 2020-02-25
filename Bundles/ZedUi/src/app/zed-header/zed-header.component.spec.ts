import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ZedHeaderComponent } from './zed-header.component';

describe('ZedHeaderComponent', () => {
  let component: ZedHeaderComponent;
  let fixture: ComponentFixture<ZedHeaderComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ZedHeaderComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ZedHeaderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
