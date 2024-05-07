import { ComponentFixture, TestBed } from '@angular/core/testing';

import { LendbookEditComponent } from './lendbook-edit.component';

describe('LendbookEditComponent', () => {
  let component: LendbookEditComponent;
  let fixture: ComponentFixture<LendbookEditComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [LendbookEditComponent]
    });
    fixture = TestBed.createComponent(LendbookEditComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
